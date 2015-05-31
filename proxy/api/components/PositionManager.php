<?php

class PositionManager
{
    
    var $x1;
    var $y1;
    var $x2;
    var $y2;
    var $lat1; 
    var $lon1;
    var $lat2; 
    var $lon2;

    public function __construct() {
        $this->self = $this;
        $this->x1 = -60.0;
        $this->y1 = -30.0;
        $this->x2 = 60.0;
        $this->y2 = 30.0;
        $this->lat1 = 46.040733;
        $this->lon1 = 11.111252;
        $this->lat2 = 46.040912; 
        $this->lon2 = 11.111772;
    }

    public function getAbsolutePosition($x, $y) {
        $phi = $this->computePhi($x, $y);
        dump('phi : ' . $phi);
        $scale = $this->getScale();
        dump('scale : '. $scale);
        $transCoord = array();
        $transCoord[0] = (($y - $this->y1) * cos($phi) - ($x - $this->x1) * sin($phi)) * $scale + $this->lat1;
        $transCoord[1] = (($y - $this->y1) * sin($phi) + ($x - $this->x1) * cos($phi)) * $scale + $this->lon1;
        return $transCoord;
    }

    // transCoord[0] = ((coord[0] - mTransBeforeRot[0]) * Math.cos(mPhi) - (coord[1] - mTransBeforeRot[1]) * Math.sin(mPhi)) * mScale + mTransAfterRot[0];
    // transCoord[1] = ((coord[0] - mTransBeforeRot[0]) * Math.sin(mPhi) + (coord[1] - mTransBeforeRot[1]) * Math.cos(mPhi)) * mScale + mTransAfterRot[1];

    private function computePhi() {
        $phiRel = $this->getAngle($y2 - $y1, $x2 - $x1);
        $phiAbs = $this->getAngle($lat2 - $lat1, $lon2 - $lon1);
        return $phiAbs - $phiRel;
    }

    private function getAngle($y, $x) {
        $phi = 0;
        $phi_base = 0;
        if ($x == 0) {
            if ($y > 0) {
                $phi_base = pi() / 2;
            } else {
                $phi_base = -pi() / 2;
            }
        } else {
            $phi_base = atan($y/$x);
        }
        if ($x < 0) {
            if ($y > 0) {
                $phi = - pi() + $phi_base;
            } else {
                $phi = pi() - $phi_base;
            }
        } else {
            $phi = $phi_base;
        }
        
        return $phi;
    }



    private function getScale() {
        $distRel = sqrt( pow(($this->x1-$this->x2),2) + pow(($this->y1-$this->y2),2) );
        dump('dist_rel : '.$distRel);
        $distAbs = sqrt( pow(($this->lat1-$this->lat2),2) + pow(($this->lon1-$this->lon2),2) );
        dump('dist_abs : '.$distAbs);
        return $distAbs / $distRel;
    }
}