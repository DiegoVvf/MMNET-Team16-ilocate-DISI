<?php

class Quuppa
{
	public static function query($tagId) {
		return Quuppa::staticResponse();
	}

	private static function staticResponse() {
		return array(
			"areaId" => "TrackingArea1",
			"areaName" => "KCM",
			"color" => "#FF0000",
			"coordinateSystemId" => "CoordinateSystem1", 
			"covarianceMatrix" => array(1.01, 0.13, 0.13, 2.2),
			"id" => "001830ecf25f", 
			"name" => "Trolley_078", 
			"position" => array(2.43, 8.07, 0.8),
			"positionAccuracy" => 1.49, 
			"positionTS" => 1401783701232, 
			"smoothedPosition" => array(2.43, 8.07, 0.8)
		);
	}
}