<?php

class Position
{

    const TYPE_ABSOLUTE = 'absolute';
    const TYPE_RELATIVE = 'relative';
    const TYPE_BOTH = 'both';

    public $lat;
    public $lon;

    public $x;
    public $y;
    public $z;

    public $type;
    public $accuracy;
    public $indoor;
    public $system;
    public $objectId;

    public $metadata;

    function __construct($system, $objectId, $metadata = array()) {
        $this->system = $system;
        $this->objectId = $objectId;
        $this->metadata = $metadata;
    }

    public function setRelativePosition($x, $y, $z, $indoor, $accuracy = null) {
        $this->type = self::TYPE_RELATIVE;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->accuracy = $accuracy;
        $this->indoor = $indoor;
    }

    public function setAbsolutePosition($lat, $lon, $indoor, $accuracy = null) {
        $this->type = self::TYPE_ABSOLUTE;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->accuracy = $accuracy;
        $this->indoor = $indoor;
    }

    public function dumpRepr($metadata = false) {
        $dumpRepr = array(
            'objectId' => $this->objectId,
            'source' => $this->system,
            'type' => $this->type,
            'accuracy' => !$this->accuracy ? -1 : $this->accuracy,
            'indoor' => $this->indoor,
        );
        if ($this->type == self::TYPE_RELATIVE) {
            $dumpRepr['position'] = array(
                'x' => $this->x,
                'y' => $this->y,
                'z' => $this->z
            );
        }
        else {
            $dumpRepr['position'] = array(
                'lat' => $this->lat,
                'lon' => $this->lon
            );
        }
        if ($metadata) $dumpRepr['metadata'] = $this->metadata;
        
        return $dumpRepr;
    }

    public static function getAvailableTypes() {
        return Position::availableTypes();
    }

    public static function isValid($positionType) {
        $availableTypes = Position::availableTypes();
        if (in_array($positionType, $availableTypes)) return true;
        else return false;
    }

    private static function availableTypes() {
        return array(
            Position::TYPE_ABSOLUTE,
            Position::TYPE_RELATIVE
        );
    }

}