<?php

/**
 * This is the model class for table "tracker".
 *
 * The followings are the available columns in table 'tracker':
 * @property integer $id
 * @property integer $id_localization_system
 * @property string $id_localization_tag
 * @property integer $id_environment
 * @property integer $id_deployment
 *
 * The followings are the available model relations:
 * @property TrackedAsset[] $trackedAssets
 * @property Environment $idEnvironment
 * @property Deployment $idDeployment
 * @property LocalizationSystem $idLocalizationSystem
 */
class Tracker extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tracker the static model class
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
		return 'tracker';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_localization_system, id_environment, id_deployment', 'required'),
			array('id_localization_system, id_environment, id_deployment', 'numerical', 'integerOnly'=>true),
			array('id_localization_tag', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_localization_system, id_localization_tag, id_environment, id_deployment', 'safe', 'on'=>'search'),
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
			'trackedAssets' => array(self::HAS_MANY, 'TrackedAsset', 'id_tracker'),
			'idEnvironment' => array(self::BELONGS_TO, 'Environment', 'id_environment'),
			'idDeployment' => array(self::BELONGS_TO, 'Deployment', 'id_deployment'),
			'idLocalizationSystem' => array(self::BELONGS_TO, 'LocalizationSystem', 'id_localization_system'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_localization_system' => 'Id Localization System',
			'id_localization_tag' => 'Id Localization Tag',
			'id_environment' => 'Id Environment',
			'id_deployment' => 'Id Deployment',
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
		$criteria->compare('id_localization_system',$this->id_localization_system);
		$criteria->compare('id_localization_tag',$this->id_localization_tag,true);
		$criteria->compare('id_environment',$this->id_environment);
		$criteria->compare('id_deployment',$this->id_deployment);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}