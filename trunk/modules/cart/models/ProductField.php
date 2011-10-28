<?php

class ProductField extends CDynamicFieldModel
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductField the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'catalogs'=>array(self::MANY_MANY, 'Catalog', '{{products_fields_by_catalog}}(fid,cid)'),
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{products_fields}}';
	}
	
	public function parentName() {
		return 'Product';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$array = parent::attributeLabels();
		$array['catalogs'] = CartModule::t('Catalogs');
		return $array;
	}
	
	public function afterSave() {
		parent::afterSave();
    	$setCat = array();
    	foreach ($this->catalogs as $catalog) {
    		$setCat[$catalog->id] = $catalog;
    	}
    	foreach ($_POST[get_class($this)]['catalogs'] as $i=>$item) {
    		if (isset($setCat[$i])) {
    			if ($item==0) {
    				ProductFieldByCatalog::model()->find(array(
						'condition'=>'cid=:cid AND fid=:fid',
						'params'=>array(':cid'=>$i,':fid'=>$this->id),
					))->delete();
    			}
    		} elseif ($item) {
    			$rel = new ProductFieldByCatalog;
    			$rel->cid = $i;
    			$rel->fid = $this->id;
    			$rel->save();
    		}
    	}
	}
	
	public function afterDelete() {
		parent::afterDelete();
		ProductFieldByCatalog::model()->deleteAll('fid='.$this->id);
	}
}