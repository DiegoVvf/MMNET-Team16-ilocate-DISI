package org.opentripplanner.routing.edgetype;

import org.opentripplanner.common.geometry.CompactLineString;
import org.opentripplanner.common.geometry.DirectionUtils;
import org.opentripplanner.routing.core.State;
import org.opentripplanner.routing.graph.Vertex;
import org.opentripplanner.routing.core.StateEditor;
import org.opentripplanner.routing.graph.Edge;

import com.vividsolutions.jts.geom.Coordinate;
import com.vividsolutions.jts.geom.LineString;

public class IndoorEdge extends Edge implements Cloneable{


	
	/**
	 * 
	 */
	private static final long serialVersionUID = 3554470150980325673L;
	private int length_mm;
	protected boolean wheelChair;
	private String name;
	public static final float DEFAULT_WALKING_SPEED = 2.0f;
	private float walkingSpeed;
	private double weightFactor;
	private StreetTraversalPermission permission;
    private int[] compactGeometry;
    /**
     * The angle at the start of the edge geometry.
     * Internal representation is -180 to +179 integer degrees mapped to -128 to +127 (brads)
     */
    private byte inAngle;

    /** The angle at the start of the edge geometry. Internal representation like that of inAngle. */
    private byte outAngle;



	public IndoorEdge(Vertex v1, Vertex v2, LineString geometry, double length, String name, StreetTraversalPermission permission) {

		super(v1, v2);
		this.setGeometry(geometry);
		this.length_mm = (int) (length * 1000); // CONVERT FROM FLOAT METERS TO FIXED MILLIMETERS
		this.name = name;
		this.wheelChair=false;
		this.setWalkingSpeed(DEFAULT_WALKING_SPEED);
		this.setPermission(permission);
		this.weightFactor = 1;
		if (geometry != null) {
            try {
                for (Coordinate c : geometry.getCoordinates()) {
                    if (Double.isNaN(c.x)) {
                        System.out.println("X DOOM");
                    }
                    if (Double.isNaN(c.y)) {
                        System.out.println("Y DOOM");
                    }
                }
                // Conversion from radians to internal representation as a single signed byte.
                // We also reorient the angles since OTP seems to use South as a reference
                // while the azimuth functions use North.
                // FIXME Use only North as a reference, not a mix of North and South!
                // Range restriction happens automatically due to Java signed overflow behavior.
                // 180 degrees exists as a negative rather than a positive due to the integer range.
                double angleRadians = DirectionUtils.getLastAngle(geometry);
                outAngle = (byte) Math.round(angleRadians * 128 / Math.PI + 128);
                angleRadians = DirectionUtils.getFirstAngle(geometry);
                inAngle = (byte) Math.round(angleRadians * 128 / Math.PI + 128);
            } catch (IllegalArgumentException iae) {
                //LOG.error("exception while determining street edge angles. setting to zero. there is probably something wrong with this street segment's geometry.");
                inAngle = 0;
                outAngle = 0;
            }
        }
		

	}

	@Override
	public State traverse(State s0) {
		StateEditor s1 = s0.edit(this);
		s1.incrementTimeInSeconds(calculateTime());
		s1.incrementWeight(calculateTime());
		return s1.makeState();
	}

	protected int calculateTime() {
		double actualSpeed = walkingSpeed * 1000 / 3600;
		return (int)(getDistance() * weightFactor / actualSpeed);
	}

	@Override
	public String getName() {
		return name;
	}

	@Override
	public double getDistance() {
		return length_mm / 1000.0; // CONVERT FROM FIXED MILLIMETERS TO FLOAT METERS
	}


	@Override
	public IndoorEdge clone() {
		try {
			return (IndoorEdge) super.clone();
		} catch (CloneNotSupportedException e) {
			throw new RuntimeException(e);
		}
	}

	public boolean isWheelchairAccessible() {
		return wheelChair;
	}

	public void setWheelChair(boolean wheelChair) {
		this.wheelChair = wheelChair;
	}

	public float getWalkingSpeed() {
		return walkingSpeed;
	}

	public void setWalkingSpeed(float walkingSpeed) {
		this.walkingSpeed = walkingSpeed;
	}


	public void setName(String name) {
		this.name = name;
	}

	public float getMaxSlope() {
		return 0.0f;
	}


	public void setWeightFactor(double weightFactor) {
		this.weightFactor = weightFactor;
	}

	public double getWeightFactor() {
		return weightFactor;
	}
	
	public void setPermission(StreetTraversalPermission permission) {
		this.permission = permission;
	}
	
	public StreetTraversalPermission getPermission() {
		return permission;
	}
	
	//back to false as default
	public LineString getGeometry() {
		return CompactLineString.uncompactLineString(fromv.getLon(), fromv.getLat(), tov.getLon(), tov.getLat(), compactGeometry, false);
	}
	//back to false as default
	private void setGeometry(LineString geometry) {
		this.compactGeometry = CompactLineString.compactLineString(fromv.getLon(), fromv.getLat(), tov.getLon(), tov.getLat(), geometry, false);
	}
	
	public int getInAngle() {
		return this.inAngle * 180 / 128;
	}

    /** Return the azimuth of the last segment in this edge in integer degrees clockwise from South. */
	public int getOutAngle() {
		return this.outAngle * 180 / 128;
	}

}
