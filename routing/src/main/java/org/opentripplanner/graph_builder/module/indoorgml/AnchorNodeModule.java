/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.opentripplanner.graph_builder.module.indoorgml;

import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.Map;
import java.util.Queue;
import java.util.Set;
import org.geotools.referencing.GeodeticCalculator;
import org.opentripplanner.graph_builder.services.GraphBuilderModule;
import org.opentripplanner.routing.edgetype.FreeEdge;
import org.opentripplanner.routing.graph.Edge;
import org.opentripplanner.routing.graph.Graph;
import org.opentripplanner.routing.graph.Vertex;
import org.opentripplanner.routing.vertextype.IndoorVertex;
import org.opentripplanner.routing.vertextype.StreetVertex;
import org.slf4j.LoggerFactory;

/**
 *
 * @author bwjoran
 */
public class AnchorNodeModule implements GraphBuilderModule {    
    private static final org.slf4j.Logger LOG = LoggerFactory.getLogger(AnchorNodeModule.class);    
    private final GeodeticCalculator geodeticCalculator = new GeodeticCalculator();    
    private Map<Vertex, Vertex> connections = new HashMap<Vertex, Vertex>();
    private Graph graph;
    
    @Override
    public void buildGraph(Graph graph, HashMap<Class<?>, Object> extra) {
        LOG.info("Connecting anchors from the indoor graphs");
        
        this.graph = graph;
        
        for(Vertex vertex : graph.getVertices()) {
            if(vertex instanceof IndoorVertex && ((IndoorVertex)vertex).getAnchorType()) {                            
                connections.put(vertex, null);
            }
        }
        
        for(Vertex vertex : connections.keySet()) {
            connectAnchorNode(vertex);
        }
        
        for(Vertex from : connections.keySet()) {
            Vertex to = connections.get(from);
            LOG.info("Connecting " + from.getLabel() + " to " + to.getLabel());
            FreeEdge freeEdge = new FreeEdge(from, to);
            FreeEdge freeEdgeBack = new FreeEdge(to, from);
            
        }
    }

    @Override
    public void checkInputs() {
    }

    /**
     * Calculate a set of all vertexes in the same subgraph
     * @param start vertex to determine the subgraph of
     * @return set of all vertices in same subgraph
     */    
    private Set<Vertex> subgraph(Vertex start) {
        Set<Vertex> graph = new HashSet<Vertex>();
        Queue<Vertex> todo = new LinkedList<Vertex>();
        
        todo.add(start);
        while(!todo.isEmpty()) {
            Vertex v = todo.poll();
            if(!graph.contains(v)) {
                graph.add(v);
            }
            for(Edge e : v.getIncoming()) {
                if(!graph.contains(e.getFromVertex())) {
                    todo.add(e.getFromVertex());
                }
            }
            for(Edge e : v.getOutgoing()) {
                if(!graph.contains(e.getToVertex())) {
                    todo.add(e.getToVertex());
                }
            }            
        }
                
        return graph;
    }
    
    private void connectAnchorNode(Vertex vertex) {
        // TODO: Use a graph index?
        Set<Vertex> subgraph = subgraph(vertex);
        geodeticCalculator.setStartingGeographicPoint(vertex.getLon(), vertex.getLat());
        Vertex foundVertex = null;
        double foundDistance = Double.POSITIVE_INFINITY;
        for(Vertex candiateVertex : graph.getVertices()) {
            if(subgraph.contains(candiateVertex)) continue;  // Exclude all vertexes in this subgraph
            
            geodeticCalculator.setDestinationGeographicPoint(candiateVertex.getLon(), candiateVertex.getLat());
            double distance = geodeticCalculator.getOrthodromicDistance() + levelDistance(vertex, candiateVertex);
            
            if(distance > foundDistance) continue;
            if(candiateVertex instanceof IndoorVertex && ((IndoorVertex)candiateVertex).getAnchorType()) continue;            
            if(!(candiateVertex instanceof StreetVertex) && !(candiateVertex instanceof IndoorVertex)) continue;
            
            foundVertex = candiateVertex;
            foundDistance = distance;
        }
        
        if(foundVertex != null) {
            connections.put(vertex, foundVertex);
        }        
    }

    private double levelDistance(Vertex vertex, Vertex candiateVertex) {
        return Math.abs(level(vertex) - level(candiateVertex)) * 10.0;
    }
    
    private double level(Vertex vertex) {
        if(vertex instanceof IndoorVertex) {
            return ((IndoorVertex)vertex).getLevel();
        }
        else {
            return 0.0;
        }
    }
    
}
