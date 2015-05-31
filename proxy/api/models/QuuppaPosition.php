<?php

/**
 * This is the model class for table "quuppa_position".
 *
 * The followings are the available columns in table 'quuppa_position':
 * @property integer $id
 * @property integer $id_deployment
 * @property string $id_tag
 * @property double $x
 * @property double $y
 * @property double $z
 * @property string $dump
 */
class QuuppaPosition extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuuppaPosition the static model class
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
		return 'quuppa_position';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_deployment, id_tag, x, y, z, dump', 'required'),
			array('id_deployment', 'numerical', 'integerOnly'=>true),
			array('x, y, z', 'numerical'),
			array('id_tag', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_deployment, id_tag, x, y, z, dump', 'safe', 'on'=>'search'),
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
			'id_deployment' => 'Id Deployment',
			'id_tag' => 'Id Tag',
			'x' => 'X',
			'y' => 'Y',
			'z' => 'Z',
			'dump' => 'Dump',
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
		$criteria->compare('id_deployment',$this->id_deployment);
		$criteria->compare('id_tag',$this->id_tag,true);
		$criteria->compare('x',$this->x);
		$criteria->compare('y',$this->y);
		$criteria->compare('z',$this->z);
		$criteria->compare('dump',$this->dump,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}