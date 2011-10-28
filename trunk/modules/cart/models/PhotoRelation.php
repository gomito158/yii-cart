<?php

/**
 * This is the model class for table "{{photos_relation}}".
 *
 * The followings are the available columns in table '{{photos_relation}}':
 * @property integer $id
 * @property integer $item_id
 * @property integer $photo_id
 * @property integer $position
 * @property integer $main
 *
 * The followings are the available model relations:
 */
class PhotoRelation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PhotoRelation the static model class
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
		return '{{photos_relation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, photo_id, position, main', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, item_id, photo_id, position, main', 'safe', 'on'=>'search'),
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
			'item_id' => 'Item',
			'photo_id' => 'Photo',
			'position' => 'Position',
			'main' => 'Main',
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
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('photo_id',$this->photo_id);
		$criteria->compare('position',$this->position);
		$criteria->compare('main',$this->main);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}