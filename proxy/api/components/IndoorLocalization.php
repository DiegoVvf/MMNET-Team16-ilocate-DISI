<?php

class IndoorLocalization
{
    public static function getPosition($objectId, $tagId, $positionType = Position::TYPE_RELATIVE) {
        $quuppaPosition = QuuppaPosition::model()->findByAttributes(array('id_tag'=>$tagId));
        if (!$quuppaPosition) {
            echo Yii::log('Quuppa position for tag '.$tagId.' is not available.', 'error', 'api');
            return null;
        }
        $position = new Position(LocalizationSystem::QUUPPA, $objectId);
        $position->setRelativePosition($quuppaPosition->x, $quuppaPosition->y, $quuppaPosition->z, true);
        return $position;
    }

}