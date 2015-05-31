package org.opentripplanner.routing.edgetype;

import org.opentripplanner.routing.core.State;
import org.opentripplanner.routing.core.StateEditor;
import org.opentripplanner.routing.graph.Vertex;

import com.vividsolutions.jts.geom.LineString;

public class IndoorElevatorEdge extends IndoorEdge{

	

 

	/**
	 * 
	 */
	private static final long serialVersionUID = -8950647269790401680L;

	public IndoorElevatorEdge(Vertex v1, Vertex v2, LineString geometry, double length, String name, StreetTraversalPermission permission) {		
	 	super(v1, v2, geometry, length, name, permission);
        this.wheelChair=true;
	        
	}

	@Override
	public State traverse(State s0) {
		StateEditor s1 = s0.edit(this);
		s1.incrementTimeInSeconds(super.calculateTime()/2);
        s1.incrementWeight(super.calculateTime()/2);
        return s1.makeState();
	}

	


    

	
	
}
