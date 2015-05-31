/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.opentripplanner.graph_builder.module.indoorgml;

import java.util.Iterator;
import javax.xml.XMLConstants;
import javax.xml.namespace.NamespaceContext;

/**
 *
 * @author bwjoran
 */
public class IndoorGmlNamspaceContext implements NamespaceContext {
    public static final String NS_GML = "http://www.opengis.net/gml/3.2";
    public static final String NS_XLINK = "http://www.w3.org/1999/xlink";
    public static final String NS_INDOOR = "http://www.opengis.net/indoorgml/1.0/core";
        
    @Override
    public String getNamespaceURI(String prefix) {
       if (prefix == null) {
            throw new IllegalArgumentException("No prefix provided!");
        } else if (prefix.equals(XMLConstants.DEFAULT_NS_PREFIX)) {
            return XMLConstants.NULL_NS_URI;
        } else if (prefix.equals("indoor")) {
            return NS_INDOOR;
        } else if (prefix.equals("xlink")) {
            return NS_XLINK;
        } else if (prefix.equals("gml")) {
            return NS_GML;
        } else {
            return XMLConstants.NULL_NS_URI;
        }    
    }

    @Override
    public String getPrefix(String string) {
        return null;
    }

    @Override
    public Iterator getPrefixes(String string) {
        return null;
    }
}
