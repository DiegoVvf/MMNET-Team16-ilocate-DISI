<?php

/**
 * This is the model class for table "last_known_position".
 *
 * The followings are the available columns in table 'last_known_position':
 * @property integer $id
 * @property string $id_tracked_asset
 * @property double $lat
 * @property double $lon
 * @property double $accuracy
 * @property string $type
 */
class LastKnownPosition extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LastKnownPosition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'last_known_position';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lat, lon, accuracy', 'required'),
			array('lat, lon, accuracy', 'numerical'),
			array('id_tracked_asset', 'length', 'max'=>64),
			array('type', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_tracked_asset, lat, lon, accuracy, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_tracked_asset' => 'Id Tracked Asset',
			'lat' => 'Lat',
			'lon' => 'Lon',
			'accuracy' => 'Accuracy',
			'type' => 'Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_tracked_asset',$this->id_tracked_asset,true);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('lon',$this->lon);
		$criteria->compare('accuracy',$this->accuracy);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}