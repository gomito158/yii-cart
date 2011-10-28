<?php

/**
 * This is the model class for table "{{orders_values}}".
 *
 * The followings are the available columns in table '{{orders_values}}':
 * @property integer $or_id
 * @property string $comment
 * @property integer $payingtype
 *
 * The followings are the available model relations:
 */
class OrderValue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderValue the static model class
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
		return '{{orders_values}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('or_id, comment', 'required'),
			array('or_id, payingtype', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('or_id, comment, payingtype', 'safe', 'on'=>'search'),
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
			'or_id' => 'Or',
			'comment' => 'Comment',
			'payingtype' => 'Payingtype',
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

		$criteria->compare('or_id',$this->or_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('payingtype',$this->payingtype);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}