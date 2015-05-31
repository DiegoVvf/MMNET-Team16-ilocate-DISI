package org.opentripplanner.routing.vertextype;

/* This program is free software: you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public License
as published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>. */


import org.opentripplanner.routing.graph.Graph;
import org.opentripplanner.routing.graph.Vertex;


public class IndoorVertex extends Vertex{


	private static final long serialVersionUID = -4660246203916176230L;
	protected double level;
	protected boolean anchorType;
	
	

    public IndoorVertex(Graph g, String label, double x, double y, double level, String name) {
        super(g, label, x, y, name);
        this.level=level;
        this.anchorType=false;

    }
    
    public double getLevel(){
    	return level;
    }
    
    public boolean getAnchorType(){
    	return anchorType;
    }
    
    public void setAnchorType(boolean anchorType){
    	this.anchorType=anchorType;
    } 

}
