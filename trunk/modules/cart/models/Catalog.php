<?php

/**
 * This is the model class for table "{{catalog}}".
 *
 * The followings are the available columns in table '{{catalog}}':
 * @property integer $id
 * @property integer $pid
 * @property string $url
 * @property string $name
 * @property string $description
 * @property integer $position
 * @property integer $status
 */
class Catalog extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_COLLECTION=2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Catalog the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return '{{catalog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, name, description', 'required'),
			array('pid, position, status', 'numerical', 'integerOnly'=>true),
			//array('url', 'unique', 'message' => CartModule::t("This url already exists.")),
			array('url', 'length', 'max'=>50),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, url, name, description, position, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'count'=>array(self::STAT,'CatalogRelation','cid'),
			'products'=>array(self::MANY_MANY, 'Product', '{{catalog_relation}}(cid, pr_id)'),
			'fields'=>array(self::MANY_MANY, 'ProductField', '{{products_fields_by_catalog}}(cid, fid)'),
			'catrelation'=>array(self::HAS_MANY,'CatalogRelation','cid'),
			'parent'=>array(self::BELONGS_TO,'Catalog','pid'),
			'child'=>array(self::HAS_MANY,'Catalog','pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => CartModule::t('Id'),
			'pid' =>  CartModule::t('Parent ID'),
			'url' => CartModule::t('Url адрес'),
			'name' => CartModule::t('Name'),
			'description' => CartModule::t('Description'),
			'position' => CartModule::t('Position'),
			'fields' => CartModule::t('Product Fields','product'),
			'status' => CartModule::t('Status'),
		);
	}
	
	public function defaultScope(){
		return array(
			'alias'=>'catalog',
			'order'=>'catalog.position asc',
        );
	}

	public function scopes(){
        return array(
            'active'=>array(
                'condition'=>'catalog.status>='.self::STATUS_ACTIVE,
            ),
            'catalog'=>array(
                'condition'=>'catalog.status='.self::STATUS_ACTIVE,
            ),
            'collection'=>array(
                'condition'=>'catalog.status='.self::STATUS_COLLECTION,
            ),
            'inorder'=>array(
                'order'=>'catalog.position asc',
            ),
            'byname'=>array(
                'catalog.order'=>'name',
            ),
            'byparent'=>array(
                'catalog.order'=>'pid',
            ),
        );
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave() {
	    parent::afterSave();
    	if (isset(Yii::app()->cache))
    		$items=Yii::app()->cache->delete(__CLASS__.'Tree');
    		
    	$setCat = array();
    	foreach ($this->fields as $filed) {
    		$setCat[$filed->id] = $filed;
    	}
    	foreach ($_POST[get_class($this)]['fields'] as $i=>$item) {
    		if (isset($setCat[$i])) {
    			if ($item==0) {
    				ProductFieldByCatalog::model()->find(array(
						'condition'=>'cid=:cid AND fid=:fid',
						'params'=>array(':fid'=>$i,':cid'=>$this->id),
					))->delete();
    			}
    		} elseif ($item) {
    			$rel = new ProductFieldByCatalog;
    			$rel->fid = $i;
    			$rel->cid = $this->id;
    			$rel->save();
    		}
    	}
	}
    
    /**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete(){
		parent::afterDelete();
		foreach ($this->child as $child)
			$child->delete();
		CatalogRelation::model()->deleteAll('cid='.$this->id);
    	if (isset(Yii::app()->cache))
    		$items=Yii::app()->cache->delete(__CLASS__.'Tree');
    	
		ProductFieldByCatalog::model()->deleteAll('cid='.$this->id);
	}
	
 	/*
     * Catalog structure
     */
    public static function getTree(){
    	if (isset(Yii::app()->cache))
    		$items=Yii::app()->cache->get(__CLASS__.'Tree');
    	else $items = false;
		if($items===false) {
			$items = array();
	    	$criteria=new CDbCriteria;
	    	$criteria->condition = 'pid=0';
			$catalogs = Catalog::model()->active()->findAll($criteria);
			foreach ($catalogs as $catalog) {
				array_push($items,array('self'=>$catalog,'childs'=>$catalog->getChilds()));
			}
	    	if (isset(Yii::app()->cache))
				Yii::app()->cache->set(__CLASS__.'Tree',$items);
		}
		return $items;
    }
    
    public function getChilds(){
    	$tree = array();
		foreach ($this->child as $child) {
			array_push($tree,array(
				'self'=>$child,
				'childs'=>$child->getChilds(),
			));
		}
		return $tree;
    }
	
	public function itemAlias($type,$code=NULL){
		$_items = array(
			'Status' => array(
				self::STATUS_NOACTIVE => CartModule::t('Not active'),
				self::STATUS_ACTIVE => CartModule::t('Active'),
				self::STATUS_COLLECTION => CartModule::t('Collection'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
}