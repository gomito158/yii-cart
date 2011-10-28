<?php

/**
 * This is the model class for table "{{products}}".
 *
 * The followings are the available columns in table '{{products}}':
 * @property integer $id
 * @property integer $photo_id
 * @property string $artno
 * @property string $url
 * @property string $tags
 * @property string $name
 * @property string $shortdesc
 * @property string $fulldesc
 * @property double $price
 * @property integer $store
 * @property integer $type
 * @property integer $status
 *
 * The followings are the available model relations:
 */
class Product extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_DELETED=-1;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
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
		return '{{products}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('photo_id, store, type, status', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('url', 'length', 'max'=>50),
			array('artno, tags, name', 'length', 'max'=>255),
			array('shortdesc, fulldesc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, artno, tags, name, shortdesc, fulldesc, price, type, status', 'safe', 'on'=>'search'),
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
			'value'=>array(self::HAS_ONE, 'ProductValue', 'pr_id'),
			'ordered'=>array(self::HAS_MANY,'OrderItem','pr_id'),
			'photo'=>array(self::BELONGS_TO,'Photo','photo_id'),
			'photos'=>array(self::MANY_MANY, 'Photo', '{{photos_relation}}(item_id, photo_id)'),
			'catalogs'=>array(self::MANY_MANY, 'Catalog', '{{catalog_relation}}(pr_id,cid)'),
			'catrelation'=>array(self::HAS_MANY,'CatalogRelation','pr_id'),
			'relation'=>array(self::MANY_MANY, 'Product', '{{products_relation}}(pid, pr_id)'),
			'traceback'=>array(self::MANY_MANY, 'Product', '{{products_relation}}(pr_id, pid)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => CartModule::t('Id'),
			'artno' => CartModule::t('Art.No.'),
			'url' => CartModule::t('Url'),
			'photo' => CartModule::t('Photo','product'),
			'catalogs' => CartModule::t('Catalogs'),
			'tags' => CartModule::t('Tags'),
			'name' => CartModule::t('Name'),
			'shortdesc' => CartModule::t('Short description'),
			'fulldesc' => CartModule::t('Full description'),
			'price' => CartModule::t('Price'),
			'store' => CartModule::t('Store'),
			'type' => CartModule::t('Type'),
			'status' => CartModule::t('Status'),
		);
	}
	

	public function scopes() {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactvie'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            'deleted'=>array(
                'condition'=>'status='.self::STATUS_DELETED,
            ),
            'admin'=>array(
                'condition'=>'status>'.self::STATUS_DELETED,
            ),
            'defsort'=>array(
            	'order'=>'t.id desc'
           	),
        );
    }
    
    /**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete() {
		parent::afterDelete();
		$this->value->delete();
		Photo::deleteByProduct($this);
		ProductRelation::model()->deleteAll('pid='.$this->id.' OR pr_id='.$this->id);
		CatalogRelation::model()->deleteAll('pr_id='.$this->id);
	} 

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('photo_id',$this->photo_id);
		$criteria->compare('artno',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('shortdesc',$this->shortdesc,true);
		$criteria->compare('fulldesc',$this->fulldesc,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('store',$this->store);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function itemAlias($type,$code=NULL) {
		$_items = array(
			'Status' => array(
				self::STATUS_NOACTIVE => CartModule::t('Hidden'),
				self::STATUS_ACTIVE => CartModule::t('Published'),
				self::STATUS_DELETED => CartModule::t('Deleted'),
			),
			'Type' => array(
				'0' => CartModule::t('Product'),
				'1' => CartModule::t('Kit'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
}