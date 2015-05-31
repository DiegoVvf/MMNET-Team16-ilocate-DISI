/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package org.opentripplanner.graph_builder.module.indoorgml;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import org.opentripplanner.graph_builder.services.GraphBuilderModule;
import org.opentripplanner.routing.graph.Graph;

/**
 *
 * @author bwjoran
 */
public class IndoorGmlModule implements GraphBuilderModule {
    private List<IndoorGmlProvider> providers = new ArrayList<IndoorGmlProvider>();
        
    /**
     * @return the providers
     */
    public List<IndoorGmlProvider> getProviders() {
        return providers;
    }

    /**
     * @param providers the providers to set
     */
    public void setProviders(List<IndoorGmlProvider> providers) {
        this.providers = providers;
    }
        
    @Override
    public void buildGraph(Graph graph, HashMap<Class<?>, Object> extra) {
        for(IndoorGmlProvider provider : providers) {
            provider.read(graph);
        }
    }

    @Override
    public void checkInputs() {
        for(IndoorGmlProvider provider : providers) {
            provider.checkInputs();
        }
    }    
}
