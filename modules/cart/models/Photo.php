<?php

/**
 * This is the model class for table "{{photos}}".
 *
 * The followings are the available columns in table '{{photos}}':
 * @property integer $id
 * @property string $prefix
 * @property string $ngroup
 * @property string $filename
 * @property integer $createdate
 * @property string $title
 * @property string $description
 * @property integer $status
 *
 * The followings are the available model relations:
 */
class Photo extends CActiveRecord {
	
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	
	private $_filesource;
	private $_im;
	private $_im2;
	private $_fileType;
	
	public $sizes = array(
		'600x600'=>array(600,600),
		'300x300'=>array(300,300),
		'100x100'=>array(100,100),
	);
	
	public $imageQuality = 100;
	
	public $sourceSaved = true;
		
	/**
	 * @var string
	 * @desc image transform (inside, outside, crop)
	 */
	public $transformMode = 'inside';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Photo the static model class
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
		return '{{photos}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createdate, status', 'numerical', 'integerOnly'=>true),
			array('prefix, ngroup', 'length', 'max'=>50),
			array('filename, title, description', 'length', 'max'=>255),
			array('filename', 'file', 'types'=>'jpg, gif, png','allowEmpty' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, prefix, ngroup, filename, createdate, title, description, status', 'safe', 'on'=>'search'),
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
			'product'=>array(self::HAS_MANY,'Product','photo_id'),
			'relation'=>array(self::HAS_MANY,'PhotoRelation','photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => CartModule::t('ID','photo'),
			'prefix' => CartModule::t('Prefix','photo'),
			'ngroup' => CartModule::t('Ngroup','photo'),
			'filename' => CartModule::t('Filename','photo'),
			'createdate' => CartModule::t('Createdate','photo'),
			'title' => CartModule::t('Title','photo'),
			'description' => CartModule::t('Description','photo'),
			'status' => CartModule::t('Status','photo'),
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
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('ngroup',$this->ngroup,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('createdate',$this->createdate);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function getType($source='') {
		if ($source) {
			$extensionName = explode('.',$source);
			$extensionName = end($extensionName);
			switch($extensionName) {
				case 'gif':
					$this->_fileType = 'image/gif';
				break;
				
				case 'png':
					$this->_fileType = 'image/png';
				break;
				
				case 'jpg':
				default:
					$this->_fileType = 'image/jpeg';
			}
		} else {
			if (isset($this->_filesource)) $this->_fileType = $this->_filesource->type;
		}
		return $this->_fileType;
	}
	
	// TODO: add beforeSave
	public function setDefault($prefix='original') {
		$this->prefix = $prefix;
		$this->ngroup();
		$this->filename=CUploadedFile::getInstance($this,'filename');
		$this->_filesource = $this->filename;
		$this->createdate = time();
		$this->status = self::STATUS_ACTIVE;
	}
	
	public function addByFile($path,$product_id) {
		if (file_exists($path)&&$product_id) {
			$this->setDefault();
			$this->filename='';
			$this->save();
			$extensionName = explode('.',$path);
			$extensionName = end($extensionName);
			$fileName = $this->id.'.'.$extensionName;
			$this->ngroup();
			$ngpoup = Yii::app()->getModule('cart')->mediaPath.$this->prefix.'/'.$this->ngroup;
			if (!file_exists($ngpoup))
				mkdir($ngpoup);
				
			if ($this->sourceSaved)
				copy($path, $ngpoup.'/'.$fileName);
				
			$this->filename=$fileName;
			if ($this->save()) {
				$this->makeAllSize($fileName,$path);
				$this->addRelation($product_id);
				unlink($path);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function addRelation($product_id,$photo_id=0) {
		if (!$photo_id) $photo_id = $this->id;
		if ($product_id&&$photo_id) {
			$photoRel = new PhotoRelation;
			$photoRel->item_id = $product_id;
			$photoRel->photo_id = $photo_id;
			$photoRel->save();
			return $photoRel->id;
		} return false;
	}
	
	public function ngroup() {
		//$this->ngroup = sprintf('%03d',$this->id);
		$this->ngroup = str_replace('.','',number_format((int)(((int)$this->id)/1000)/100,2));
	}
	
	// TODO: adda afterSave
	public function fullSave() {
		$fileName = $this->id.'.'.$this->filename->extensionName;
		$this->ngroup();
		$ngpoup = Yii::app()->getModule('cart')->mediaPath.$this->prefix.'/'.$this->ngroup;
		if (!file_exists($ngpoup))
			mkdir($ngpoup);
		
		$this->filename->saveAs($ngpoup.'/'.$fileName);
		$this->filename=$fileName;
		$this->save();
		$this->makeAllSize($fileName,$ngpoup.'/'.$fileName);
		if ($this->sourceSaved==false) {
			unlink($ngpoup.'/'.$fileName);
		}
	}
	
	public function makeAllSize($fileName,$source='') {
		
		if ($this->sizes) {
			foreach ($this->sizes as $prefix=>$size) {
				$ngpoup = Yii::app()->getModule('cart')->mediaPath.$prefix.'/'.$this->ngroup;
				$outfile = $ngpoup.'/'.$fileName;
				//echo "<pre>"; print_r(array($outfile,$size[0],$size[1],$this->imageQuality)); die();
				if (!file_exists($ngpoup))
					mkdir($ngpoup,0777,true);
				$this->imageResize($outfile,$size[0],$size[1],$source);
			}
		}
	}
	
	private function imageCreate($source='') {
		$type = $this->getType($source);
		if (!$source) {
			$source = $this->_filesource->tempName;
		}
		
		if (($type=="image/jpeg") or ($type=="image/pjpeg")) {
			if ($this->_im = imagecreatefromjpeg($source))
				return true; 
		}
		if ($type=="image/png") {
			if ($this->_im = imagecreatefrompng($source))
				return true; 
		}
		if ($type=="image/gif") {
			if ($this->_im = imagecreatefromgif($source))
				return true; 
		}
		return false;
	}
	
	
	public function imageSave($outfile) {
		$type = $this->_fileType;
		if (($type=="image/jpeg") or ($type=="image/pjpeg")) {
			imagejpeg($this->_im2,$outfile,$this->imageQuality);
		}
		if ($type=="image/png") {
			imagepng($this->_im2,$outfile);
		}
		if ($type=="image/gif") {
			imagegif($this->_im2,$outfile);
		}
	}
	
	static public function deleteByProduct($product) {
		foreach ($product->photos as $photo)
			$photo->deletePhoto($product->id);
	}
	
	public function deletePhoto($item_id) {
		$safe = false;
		if (count($this->relation)>1)
			$safe = true;
		
		if ($safe)
			$relation = PhotoRelation::model()->find(array(
					'condition'=>'item_id=:item_id AND photo_id=:photo_id',
					'params'=>array(':item_id'=>$item_id,':photo_id'=>$this->id),
				))->delete();
		else {
			$this->delete();
		}
	}
    
    /**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete() {
		PhotoRelation::model()->deleteAll(
			'photo_id=:photo_id',
			array(':photo_id'=>$this->id)
		);
		// Delete original file
		if ($this->sourceSaved) {
			$path = $this->path();
			if (strpos($path,'/')===0)
				$path = substr($path,1);
			unlink($path);
		}
		// Delete all sizes files
		foreach ($this->sizes as $prefix=>$size) {
			$path = $this->path($prefix);
			if (strpos($path,'/')===0)
				$path = substr($path,1);
			if (file_exists($path))
				unlink($path);
		}
	}
	
	//-------------------------------------------------------//
	//---- Image resize -------------------------------------//
	function imageResize($outfile,$neww,$newh,$source='') {
		
		if ($this->imageCreate($source)) {
			$k1=$neww/imagesx($this->_im);
			$k2=$newh/imagesy($this->_im);
			// inside, outside, crop
			if ($this->transformMode=='inside')
				$k=$k1>$k2?$k2:$k1;
			else
				$k=$k1<$k2?$k2:$k1;
		
			$w=intval(imagesx($this->_im)*$k);
			$h=intval(imagesy($this->_im)*$k);
			// TODO: Crop method
			$this->_im2=imagecreatetruecolor($w,$h);
			imagecopyresampled($this->_im2,$this->_im,0,0,0,0,$w,$h,imagesx($this->_im),imagesy($this->_im));
			$this->imageSave($outfile);
		}
	}
	
	public function path($size='original') {
		// TODO: dynamic size
		return '/'.Yii::app()->getModule('cart')->mediaPath.((in_array($size,$this->sizes))?$size:$this->prefix).'/'.$this->ngroup.'/'.$this->filename;
	}
	
	public function img($params=array()) {
		return '<img src="'.((isset($params['size']))?$this->path($params['size']):$this->path()).'"'
		.((isset($params['w']))?' width="'.$params['w'].'"':'')
		.((isset($params['h']))?' height="'.$params['h'].'"':'')
		.((isset($params['alt']))?' alt="'.$params['alt'].'"':'')
		.((isset($params['img_title']))?' title="'.$params['img_title'].'"':'')
		.((isset($params['img_class']))?' class="'.$params['img_class'].'"':'')
		.((isset($params['img_id']))?' id="'.$params['img_id'].'"':'')
		.((isset($params['border']))?' border="'.$params['border'].'"':' border="0"')
		.' />';
	}
	
	public function link($params=array()) {
		return '<a href="'.((isset($params['link_size']))?$this->path($params['link_size']):$this->path()).'"'
		.((isset($params['link_title']))?' title="'.$params['link_title'].'"':'')
		.((isset($params['link_class']))?' class="'.$params['link_class'].'"':'')
		.((isset($params['link_id']))?' id="'.$params['link_id'].'"':'')
		.((isset($params['target']))?' target="'.$params['target'].'"':'')
		.((isset($params['rel']))?' rel="'.$params['rel'].'"':'')
		.'>'
		.((isset($params['inside']))?$params['inside']:$this->img($params))
		.'</a>';
	}
}