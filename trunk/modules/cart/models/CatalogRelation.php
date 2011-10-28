<?php

/**
 * This is the model class for table "{{catalog_relation}}".
 *
 * The followings are the available columns in table '{{catalog_relation}}':
 * @property integer $id
 * @property integer $cid
 * @property integer $pr_id
 */
class CatalogRelation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CatalogRelation the static model class
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
		return '{{catalog_relation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, pr_id', 'numerical', 'integerOnly'=>true),
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
			'relation'=>array(self::BELONGS_TO,'Product','pr_id'),
			'catrelation'=>array(self::BELONGS_TO,'Catalog','cid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cid' => 'Cid',
			'pr_id' => 'Pr',
		);
	}
}