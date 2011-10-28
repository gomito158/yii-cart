<?php

class CDynamicFieldValue extends CActiveRecord {
	
	private $_model;
	public $ownerField = 'owner_id';

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$required = array();
		$numerical = array();		
		$rules = array();
		
		$model=$this->_getModel();
		
		foreach ($model as $field) {
			$field_rule = array();
			if ($field->required==CDynamicFieldModel::REQUIRED_YES)
				array_push($required,$field->varname);
			if ($field->field_type=='FLOAT'||$field->field_type=='INTEGER')
				array_push($numerical,$field->varname);
			if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
				$field_rule = array($field->varname, 'length', 'max'=>$field->field_size, 'min' => $field->field_size_min);
				if ($field->error_message) $field_rule['message'] = CartModule::t($field->error_message,$this->parentName());
				array_push($rules,$field_rule);
			}
			if ($field->other_validator) {
				if (strpos($field->other_validator,'{')===0) {
					$validator = (array)CJavaScript::jsonDecode($field->other_validator);
					$field_rule = array($field->varname, key($validator));
					$field_rule = array_merge($field_rule,(array)$validator[key($validator)]);
				} else {
					$field_rule = array($field->varname, $field->other_validator);
				}
				if ($field->error_message) $field_rule['message'] = CartModule::t($field->error_message,$this->parentName());
				array_push($rules,$field_rule);
			} elseif ($field->field_type=='DATE') {
				$field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd', 'allowEmpty'=>true);
				if ($field->error_message) $field_rule['message'] = CartModule::t($field->error_message,$this->parentName());
				array_push($rules,$field_rule);
			}
			if ($field->match) {
				$field_rule = array($field->varname, 'match', 'pattern' => $field->match);
				if ($field->error_message) $field_rule['message'] = CartModule::t($field->error_message,$this->parentName());
				array_push($rules,$field_rule);
			}
			if ($field->range) {
				$field_rule = array($field->varname, 'in', 'range' => $this->rangeRules($field->range));
				if ($field->error_message) $field_rule['message'] = CartModule::t($field->error_message,$this->parentName());
				array_push($rules,$field_rule);
			}
		}
		
		array_push($rules,array(implode(',',$required), 'required'));
		array_push($rules,array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));
		return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			$this->parentName()=>array(self::HAS_ONE, $this->parentName(), $this->ownerField),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 *///*
	public function attributeLabels()
	{
		$labels = array(
			$this->ownerField => CartModule::t('Owner ID',$this->parentName()),
		);
		$model=$this->_getModel();
		
		foreach ($model as $field)
			$labels[$field->varname] = CartModule::t($field->title,$this->parentName());
			
		return $labels;
	}//*/
	
	private function rangeRules($str) {
		$rules = explode(';',$str);
		for ($i=0;$i<count($rules);$i++)
			$rules[$i] = current(explode("==",$rules[$i]));
		return $rules;
	}
	
	public function range($str,$fieldValue=NULL) {
		$rules = explode(';',$str);
		$array = array();
		for ($i=0;$i<count($rules);$i++) {
			$item = explode("==",$rules[$i]);
			if (isset($item[0])) $array[$item[0]] = ((isset($item[1]))?$item[1]:$item[0]);
		}
		if (isset($fieldValue)) 
			if (isset($array[$fieldValue])) return $array[$fieldValue]; else return '';
		else
			return $array;
	}
	
	public function widgetAttributes() {
		$data = array();
		$model=$this->_getModel();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widget;
		}
		return $data;
	}
	
	public function widgetParams($fieldName) {
		$data = array();
		$model=$this->_getModel();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widgetparams;
		}
		return $data[$fieldName];
	}
	
	public function getFields() {
		return $this->_getModel();
	}
	
	private function _getModel() {
		if (!$this->_model) {
			$r = call_user_func(array($this->fieldModel(),'model'));
			$this->_model=$r->forOwner()->findAll();
		}
		return $this->_model;
	}
}