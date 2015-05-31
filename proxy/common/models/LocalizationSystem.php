<?php

/**
 * This is the model class for table "localization_system".
 *
 * The followings are the available columns in table 'localization_system':
 * @property integer $id
 * @property integer $id_type
 * @property integer $id_environment
 * @property integer $id_deployment
 *
 * The followings are the available model relations:
 * @property Deployment $idDeployment
 * @property LocalizationSystemType $idType
 * @property Environment $idEnvironment
 */
class LocalizationSystem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LocalizationSystem the static model class
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
		return 'localization_system';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_type, id_environment, id_deployment', 'required'),
			array('id_type, id_environment, id_deployment', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_type, id_environment, id_deployment', 'safe', 'on'=>'search'),
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
			'idDeployment' => array(self::BELONGS_TO, 'Deployment', 'id_deployment'),
			'idType' => array(self::BELONGS_TO, 'LocalizationSystemType', 'id_type'),
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
			'id_type' => 'Id Type',
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
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('id_environment',$this->id_environment);
		$criteria->compare('id_deployment',$this->id_deployment);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}