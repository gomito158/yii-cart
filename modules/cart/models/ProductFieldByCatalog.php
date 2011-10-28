<?php

/**
 * This is the model class for table "{{products_fields_by_catalog}}".
 *
 * The followings are the available columns in table '{{products_fields_by_catalog}}':
 * @property integer $id
 * @property integer $fid
 * @property integer $cid
 * @property string $range
 * @property integer $filter
 * @property integer $required
 *
 * The followings are the available model relations:
 */
class ProductFieldByCatalog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductsFieldsByCatalog the static model class
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
		return '{{products_fields_by_catalog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('range', 'required'),
			array('fid, cid, filter, required', 'numerical', 'integerOnly'=>true),
			array('range', 'length', 'max'=>5000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fid, cid, range, filter, required', 'safe', 'on'=>'search'),
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
			'field'=>array(self::BELONGS_TO,'ProductField','fid'),
			'catalog'=>array(self::BELONGS_TO,'Catalog','cid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fid' => 'Field id',
			'cid' => 'Catalog id',
			'range' => 'Range',
			'filter' => 'Filter',
			'required' => 'Required',
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
		$criteria->compare('fid',$this->fid);
		$criteria->compare('cid',$this->cid);
		$criteria->compare('range',$this->range,true);
		$criteria->compare('filter',$this->filter);
		$criteria->compare('required',$this->required);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}