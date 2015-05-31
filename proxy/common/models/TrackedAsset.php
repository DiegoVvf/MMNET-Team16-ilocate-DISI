<?php

/**
 * This is the model class for table "tracked_asset".
 *
 * The followings are the available columns in table 'tracked_asset':
 * @property integer $id
 * @property integer $id_asset
 * @property integer $id_deployment
 * @property integer $id_environment
 * @property integer $id_tracker
 *
 * The followings are the available model relations:
 * @property Tracker $idTracker
 * @property Asset $idAsset
 * @property Deployment $idDeployment
 * @property Environment $idEnvironment
 */
class TrackedAsset extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TrackedAsset the static model class
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
		return 'tracked_asset';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_asset, id_deployment, id_environment, id_tracker', 'required'),
			array('id_asset, id_deployment, id_environment, id_tracker', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_asset, id_deployment, id_environment, id_tracker', 'safe', 'on'=>'search'),
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
			'idTracker' => array(self::BELONGS_TO, 'Tracker', 'id_tracker'),
			'idAsset' => array(self::BELONGS_TO, 'Asset', 'id_asset'),
			'idDeployment' => array(self::BELONGS_TO, 'Deployment', 'id_deployment'),
			'idEnvironment' => array(self::BELONGS_TO, 'Environment', 'id_environment'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_asset' => 'Id Asset',
			'id_deployment' => 'Id Deployment',
			'id_environment' => 'Id Environment',
			'id_tracker' => 'Id Tracker',
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
		$criteria->compare('id_asset',$this->id_asset);
		$criteria->compare('id_deployment',$this->id_deployment);
		$criteria->compare('id_environment',$this->id_environment);
		$criteria->compare('id_tracker',$this->id_tracker);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}