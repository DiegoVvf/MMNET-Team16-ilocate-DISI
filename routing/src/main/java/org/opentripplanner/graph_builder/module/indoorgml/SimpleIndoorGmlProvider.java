/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package org.opentripplanner.graph_builder.module.indoorgml;

import com.vividsolutions.jts.geom.Coordinate;
import com.vividsolutions.jts.geom.CoordinateSequence;
import com.vividsolutions.jts.geom.GeometryFactory;
import com.vividsolutions.jts.geom.LineString;
import com.vividsolutions.jts.geom.impl.CoordinateArraySequence;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathFactory;

import org.geotools.referencing.GeodeticCalculator;
import org.opentripplanner.routing.edgetype.IndoorEdge;
import org.opentripplanner.routing.edgetype.IndoorElevatorEdge;
import org.opentripplanner.routing.edgetype.IndoorStairsEdge;
import org.opentripplanner.routing.edgetype.StreetTraversalPermission;
import org.opentripplanner.routing.graph.Graph;
import org.opentripplanner.routing.graph.Vertex;
import org.opentripplanner.routing.vertextype.IndoorDoorVertex;
import org.opentripplanner.routing.vertextype.IndoorVertex;
import org.slf4j.LoggerFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

/**
 *
 * @author bwjoran
 */
public class SimpleIndoorGmlProvider implements IndoorGmlProvider {
    private static final org.slf4j.Logger LOG = LoggerFactory.getLogger(IndoorGmlProvider.class);
        
    private File path;
    
    private Document doc;
    private final XPath xpath;
    private final GeometryFactory geometryFactory = new GeometryFactory();
    private final GeodeticCalculator geodeticCalculator = new GeodeticCalculator();

    public SimpleIndoorGmlProvider() {
        xpath = XPathFactory.newInstance().newXPath();        
        xpath.setNamespaceContext(new IndoorGmlNamspaceContext());    
    }       

    public SimpleIndoorGmlProvider(File indoorGmlFile) {
        xpath = XPathFactory.newInstance().newXPath();        
        xpath.setNamespaceContext(new IndoorGmlNamspaceContext());    
        path = indoorGmlFile;
    }
    
    private Coordinate getCoordinate(Element element) throws XPathExpressionException {
        String s = (String) xpath.evaluate(".", element, XPathConstants.STRING);
        String[] coordinates = s.split(" ");        
        return new Coordinate(Double.parseDouble(coordinates[0]), Double.parseDouble(coordinates[1]), Double.parseDouble(coordinates[2]));        
    }
    
    private LineString getLineString(Element element) throws XPathExpressionException {
        NodeList posses = (NodeList) xpath.evaluate(".//gml:pos", element, XPathConstants.NODESET);
        CoordinateSequence sequence = new CoordinateArraySequence(posses.getLength());
        for(int i = 0; i < posses.getLength(); i++) {            
            Element pos = (Element) posses.item(i);
            Coordinate coordinate = sequence.getCoordinate(i);
            coordinate.setCoordinate(getCoordinate(pos));
        }
                
        return new LineString(sequence, geometryFactory);
    }
    
    private double length(LineString lineString) {
        double length = 0;
        for(int i = 0; i < lineString.getNumPoints() - 1; i++) {
            Coordinate startPos = lineString.getCoordinateN(i);
            Coordinate endPos = lineString.getCoordinateN(i + 1);
            geodeticCalculator.setStartingGeographicPoint(startPos.x, startPos.y);
            geodeticCalculator.setDestinationGeographicPoint(endPos.x, endPos.y);
            length += geodeticCalculator.getOrthodromicDistance();
        }
        
        return length;
    }
    
    private double length(Vertex from, Vertex to) {
        geodeticCalculator.setStartingGeographicPoint(from.getLon(), from.getLat());
        geodeticCalculator.setDestinationGeographicPoint(to.getLon(), to.getLat());
        return geodeticCalculator.getOrthodromicDistance();
    }
        
    @Override
    public void read(Graph graph) {
        
        Map<String, Vertex> vertices = new HashMap<String, Vertex>();
        
        try {
            NodeList states = (NodeList) xpath.evaluate("//indoor:SpaceLayer/indoor:navigationType[text()='WALK']/..//indoor:State", doc, XPathConstants.NODESET);
            for(int i = 0; i < states.getLength(); i++) {
                Element state = (Element) states.item(i);
                String id = state.getAttributeNS(IndoorGmlNamspaceContext.NS_GML, "id");
                boolean isDoor = state.getAttribute("isDoor").toLowerCase().equals("true");
                boolean isAnchor = state.getAttribute("isAnchorNode").toLowerCase().equals("true");
                String name = (String) xpath.evaluate("./gml:name", state, XPathConstants.STRING);
                String description = (String) xpath.evaluate("./gml:description", state, XPathConstants.STRING);
                Coordinate coordinate = getCoordinate((Element) xpath.evaluate(".//gml:pos", state, XPathConstants.NODE));
                                
                IndoorVertex vertex = null;
                if(isDoor) {
                    // TODO: Level should not be a short
                    vertex = new IndoorDoorVertex(graph, id, coordinate.x, coordinate.y, coordinate.z, name);
                }
                else {
                    vertex = new IndoorVertex(graph, id, coordinate.x, coordinate.y, coordinate.z, name);
                }
                vertex.setAnchorType(isAnchor);                
                vertices.put(id, vertex);                                
                LOG.info("Created indoor vertex " + id);
            }            
        } catch (XPathExpressionException ex) {
            throw new RuntimeException(ex);
        }
        
        try {
            NodeList transitions = (NodeList) xpath.evaluate("//indoor:SpaceLayer/indoor:navigationType[text()='WALK']/..//indoor:Transition", doc, XPathConstants.NODESET);
            for(int i = 0; i < transitions.getLength(); i++) {
                Element transition = (Element) transitions.item(i);                
                String  startRef = (String) xpath.evaluate("./indoor:connects[1]/@xlink:href", transition, XPathConstants.STRING);                
                String  endRef = (String) xpath.evaluate("./indoor:connects[2]/@xlink:href", transition, XPathConstants.STRING);                
                String transitionType = (String) xpath.evaluate("./indoor:transitionType", transition, XPathConstants.STRING);                
                Double weightFactor = (Double) xpath.evaluate("./indoor:weight", transition, XPathConstants.NUMBER);
                if(weightFactor == null) weightFactor = 1.0;
                LineString lineString = getLineString(transition);
                Vertex startVertex = vertices.get(startRef.substring(1));
                Vertex endVertex = vertices.get(endRef.substring(1));
                
                IndoorEdge edge = null;
                switch (transitionType) {
                    case "STAIRS":
                        // TODO: Vertex should not be an IndoorVertex
                        edge = new IndoorStairsEdge(startVertex, endVertex, lineString, length(lineString), null, StreetTraversalPermission.PEDESTRIAN);
                        break;
                    case "ELEVATOR":
                        edge = new IndoorElevatorEdge(startVertex, endVertex, lineString, length(lineString), null, StreetTraversalPermission.PEDESTRIAN);
                        break;
                    default:
                        edge = new IndoorEdge(startVertex, endVertex, lineString, length(lineString), null, StreetTraversalPermission.PEDESTRIAN);
                        break;
                }
                // TODO: edge.setWeightFactor(weightFactor);
                LOG.info("Created indoor edge from " + startVertex.getLabel() + " to " + endVertex.getLabel() + " length: " + edge.getDistance());                
            }            
        } catch (XPathExpressionException ex) {
            throw new RuntimeException(ex);
        }        
    }

    @Override
    public void checkInputs() {
        try {
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            factory.setNamespaceAware(true);
            DocumentBuilder builder = factory.newDocumentBuilder();
            doc = builder.parse(path);
        } catch (Exception ex) {
            throw new RuntimeException(ex);
        }
    }        

    /**
     * @return the path
     */
    public File getPath() {
        return path;
    }

    /**
     * @param path the path to set
     */
    public void setPath(File path) {
        this.path = path;
    }
}
