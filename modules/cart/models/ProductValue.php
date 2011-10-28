<?php

class ProductValue extends CDynamicFieldValue {
	
	public $ownerField = 'pr_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductValue the static model class
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
		return '{{products_values}}';
	}
	
	public function parentName() {
		return 'Product';
	}
	
	public function fieldModel() {
		return 'ProductField';
	}
}