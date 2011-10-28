<?php

class ImportForm extends CFormModel {
	
	public $importFile;
	
	/**
	 * @return array relational rules.
	 */
	public function rules() {
		return array(
			array('importFile', 'file', 'types'=>'csv,xml','allowEmpty' => false),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'importFile'=>CartModule::t('Import from file','import'),
		);
	}
}