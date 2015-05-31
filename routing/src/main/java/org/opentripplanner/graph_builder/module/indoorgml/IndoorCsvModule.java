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

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import org.geotools.referencing.GeodeticCalculator;
import org.opentripplanner.graph_builder.services.GraphBuilderModule;
import org.opentripplanner.routing.edgetype.IndoorEdge;
import org.opentripplanner.routing.edgetype.IndoorElevatorEdge;
import org.opentripplanner.routing.edgetype.IndoorStairsEdge;
import org.opentripplanner.routing.edgetype.StreetTraversalPermission;
import org.opentripplanner.routing.graph.Graph;
import org.opentripplanner.routing.graph.Vertex;
import org.opentripplanner.routing.vertextype.IndoorDoorVertex;
import org.opentripplanner.routing.vertextype.IndoorElevatorVertex;
import org.opentripplanner.routing.vertextype.IndoorVertex;
import org.slf4j.LoggerFactory;

/**
 * A temporary class to create an indoor graph. This will be used until we
 * have access to good indoorgml files.
 * @author bwjoran
 */
public class IndoorCsvModule implements GraphBuilderModule {
    private static final org.slf4j.Logger LOG = LoggerFactory.getLogger(IndoorGmlProvider.class);
    private final GeodeticCalculator geodeticCalculator = new GeodeticCalculator();
    private final GeometryFactory geometryFactory = new GeometryFactory();
    private Graph graph;
    private final File path;
    private final List<IndoorVertex> vertices = new LinkedList<IndoorVertex>();
    private static int vertexIndex = 0;
    private static int edgeIndex = 0;

    
    public IndoorCsvModule(File path) {
        this.path = path;
    }
    
    @Override
    public void buildGraph(Graph graph, HashMap<Class<?>, Object> extra) {
        LOG.info("Building indoor graph from {}", path);
        
        this.graph = graph;
        
        try {
            BufferedReader reader = new BufferedReader(new FileReader(path));
            reader.readLine(); // header
            String line = reader.readLine();
            while(line != null) {                
                String[] items = line.split(",");
                
                String anchor = getString(items, 6);
                String edgeType = getString(items, 7);
                String fromType = getString(items, 8);
                String toType = getString(items, 9);
                String fromName = getString(items, 10);
                String toName = getString(items, 11);
                                                
                IndoorVertex from = getOrCreateVertex(Double.parseDouble(items[0]), Double.parseDouble(items[1]), Double.parseDouble(items[2]), fromType, fromName);
                if(anchor.equals("S") || anchor.equals("B")) from.setAnchorType(true);
                IndoorVertex to = getOrCreateVertex(Double.parseDouble(items[3]), Double.parseDouble(items[4]), Double.parseDouble(items[5]), toType, toName);
                if(anchor.equals("E") || anchor.equals("B")) from.setAnchorType(true);
                                                             
                LineString lineString = createLineString(from, to);
                IndoorEdge edge;
                IndoorEdge backEdge;
                switch (edgeType) {
                    case "S":
                        edge = new IndoorStairsEdge(from, to, lineString, length(from, to), "Stairs" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);
                        backEdge = new IndoorStairsEdge(to, from, lineString, length(from, to), "Stairs" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);                    
                        break;
                    case "E":
                        edge = new IndoorElevatorEdge(from, to, lineString, length(from, to), "Elevator" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);
                        backEdge = new IndoorElevatorEdge(to, from, lineString, length(from, to), "Elevator" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);                    
                        break;
                    default:
                        edge = new IndoorEdge(from, to, lineString, length(from, to), "Corridor" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);
                        backEdge = new IndoorEdge(to, from, lineString, length(from, to), "Corridor" + edgeIndex, StreetTraversalPermission.PEDESTRIAN);
                        break;                        
                }
                edgeIndex += 1;
                
                line = reader.readLine();
            }
        } catch (FileNotFoundException ex) {
            Logger.getLogger(IndoorCsvModule.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ex) {
            Logger.getLogger(IndoorCsvModule.class.getName()).log(Level.SEVERE, null, ex);
        }        
    }        

    @Override
    public void checkInputs() {
    }    

    private IndoorVertex getOrCreateVertex(double x, double y, double level, String vtype, String name) {
        IndoorVertex vertex = findVertex(x, y, level);
        if(vertex == null) {
            switch (vtype) {
                case "D":            
                    vertex = new IndoorDoorVertex(graph, "Indoor" + vertexIndex, x, y, level, name);
                    break;
                case "E":
                    vertex = new IndoorElevatorVertex(graph, "Indoor" + vertexIndex, x, y, level, name);
                    break;
                default:
                    vertex = new IndoorVertex(graph, "Indoor" + vertexIndex, x, y, level, name);
                    break;
            }
            vertices.add(vertex);
            vertexIndex += 1;
        }
                
        return vertex;
    }

    private double length(IndoorVertex from, IndoorVertex to) {
        geodeticCalculator.setStartingGeographicPoint(from.getLon(), from.getLat());
        geodeticCalculator.setDestinationGeographicPoint(to.getLon(), to.getLat());
        return geodeticCalculator.getOrthodromicDistance();
    }

    private IndoorVertex findVertex(double x, double y, double level) {
        geodeticCalculator.setStartingGeographicPoint(x, y);
        for(IndoorVertex vertex : vertices) {
            if(vertex.getLevel() != level) continue;
            geodeticCalculator.setDestinationGeographicPoint(vertex.getLon(), vertex.getLat());
            if(geodeticCalculator.getOrthodromicDistance() > 0.1) continue;
            return vertex;
        }
        
        return null;
    }
    
    private LineString createLineString(Vertex from, Vertex to) {
        CoordinateSequence coordinateSequence = new CoordinateArraySequence(2);
        Coordinate fromCoordiante = coordinateSequence.getCoordinate(0);
        fromCoordiante.x = from.getLon();
        fromCoordiante.y = from.getLat();
        fromCoordiante.z = Double.NaN;        
        Coordinate toCoordiante = coordinateSequence.getCoordinate(1);
        toCoordiante.x = to.getLon();
        toCoordiante.y = to.getLat();
        toCoordiante.z = Double.NaN;        
        
        return new LineString(coordinateSequence, geometryFactory);
    }    

    private String getString(String[] items, int i) {
        if(items.length > i) return items[i];
        return "";
    }
}
