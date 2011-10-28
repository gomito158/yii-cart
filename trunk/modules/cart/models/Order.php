<?php

/**
 * This is the model class for table "{{orders}}".
 *
 * The followings are the available columns in table '{{orders}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $manger_id
 * @property string $manger_comments
 * @property integer $createdate
 * @property integer $finishdate
 * @property double $payable
 * @property integer $status
 *
 * The followings are the available model relations:
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
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
		return '{{orders}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('manger_comments', 'required'),
			array('user_id, manger_id, createdate, finishdate, status', 'numerical', 'integerOnly'=>true),
			array('payable', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, manger_id, manger_comments, createdate, finishdate, payable, status', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'manger_id' => 'Manger',
			'manger_comments' => 'Manger Comments',
			'createdate' => 'Createdate',
			'finishdate' => 'Finishdate',
			'payable' => 'Payable',
			'status' => 'Status',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('manger_id',$this->manger_id);
		$criteria->compare('manger_comments',$this->manger_comments,true);
		$criteria->compare('createdate',$this->createdate);
		$criteria->compare('finishdate',$this->finishdate);
		$criteria->compare('payable',$this->payable);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}