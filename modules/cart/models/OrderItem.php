<?php

/**
 * This is the model class for table "{{orders_items}}".
 *
 * The followings are the available columns in table '{{orders_items}}':
 * @property integer $id
 * @property integer $or_id
 * @property integer $pr_id
 * @property integer $user_id
 * @property integer $quan
 * @property double $price
 * @property string $product_name
 *
 * The followings are the available model relations:
 */
class OrderItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderItem the static model class
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
		return '{{orders_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_name', 'required'),
			array('or_id, pr_id, user_id, quan', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('product_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, or_id, pr_id, user_id, quan, price, product_name', 'safe', 'on'=>'search'),
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
			'or_id' => 'Or',
			'pr_id' => 'Pr',
			'user_id' => 'User',
			'quan' => 'Quan',
			'price' => 'Price',
			'product_name' => 'Product Name',
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
		$criteria->compare('or_id',$this->or_id);
		$criteria->compare('pr_id',$this->pr_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('quan',$this->quan);
		$criteria->compare('price',$this->price);
		$criteria->compare('product_name',$this->product_name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}