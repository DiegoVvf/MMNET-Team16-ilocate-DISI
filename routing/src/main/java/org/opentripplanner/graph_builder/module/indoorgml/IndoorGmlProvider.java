/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package org.opentripplanner.graph_builder.module.indoorgml;

import org.opentripplanner.routing.graph.Graph;

/**
 *
 * @author bwjoran
 */
public interface IndoorGmlProvider {
    public void read(Graph graph);
    
    /** @see GraphBuilder.checkInputs() */
    public void checkInputs();   
}
