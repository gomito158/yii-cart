<?php

/**
 * This is the model class for table "{{products_fields}}".
 *
 * The followings are the available columns in table '{{products_fields}}':
 * @property integer $id
 * @property string $varname
 * @property string $title
 * @property string $field_type
 * @property integer $field_size
 * @property integer $field_size_min
 * @property integer $required
 * @property string $match
 * @property string $range
 * @property string $error_message
 * @property string $other_validator
 * @property string $default
 * @property string $widget
 * @property string $widgetparams
 * @property integer $position
 * @property integer $visible
 *
 * The followings are the available model relations:
 */
class CDynamicFieldModel extends CActiveRecord {
	
	const VISIBLE_NO=0;
	const VISIBLE_ONLY_OWNER=1;
	const VISIBLE_REGISTER_USER=2;
	const VISIBLE_ALL=3;
	
	const REQUIRED_NO = 0;
	const REQUIRED_YES = 1;
	
	private static $_widgets = array();
	
	public $pageSize = 20;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('varname, title, field_type', 'required'),
			array('varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u','message' => CartModule::t("Variable name may consist of A-z, 0-9, underscores, begin with a letter.")),
			array('varname', 'unique', 'message' => CartModule::t("This field already exists.")),
			array('varname, field_type', 'length', 'max'=>50),
			array('field_size, field_size_min, required, position, visible', 'numerical', 'integerOnly'=>true),
			array('title, match, error_message, other_validator, default, widget', 'length', 'max'=>255),
			array('range, widgetparams', 'length', 'max'=>5000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, varname, title, field_type, field_size, field_size_min, required, match, range, error_message, other_validator, default, widget, widgetparams, position, visible', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => CartModule::t('ID'),
			'varname' => CartModule::t('Variable name'),
			'title' => CartModule::t('Title'),
			'field_type' => CartModule::t('Field Type'),
			'field_size' => CartModule::t('Field Size'),
			'field_size_min' => CartModule::t('Min Field Size'),
			'required' => CartModule::t('Required'),
			'match' => CartModule::t('Match'),
			'range' => CartModule::t('Range'),
			'error_message' => CartModule::t('Error message'),
			'other_validator' => CartModule::t('Other validator'),
			'default' => CartModule::t('Default'),
			'widget' => CartModule::t('Widget'),
			'widgetparams' => CartModule::t('Widget parameters'),
			'position' => CartModule::t('Position'),
			'visible' => CartModule::t('Visible'),
		);
	}
	
	public function defaultScope() {
		return array(
			'alias'=>$this->parentName(),
        	'order'=>$this->parentName().'.position',
		);
	}
	
	public function scopes()
    {
        return array(
            'forAll'=>array(
				'alias'=>$this->parentName(),
                'condition'=>$this->parentName().'.visible='.self::VISIBLE_ALL,
            ),
            'forUser'=>array(
				'alias'=>$this->parentName(),
                'condition'=>$this->parentName().'.visible>='.self::VISIBLE_REGISTER_USER,
            ),
            'forOwner'=>array(
				'alias'=>$this->parentName(),
                'condition'=>$this->parentName().'.visible>='.self::VISIBLE_ONLY_OWNER,
            ),
            'sortid'=>array(
				'alias'=>$this->parentName(),
                'order'=>$this->parentName().'.id',
            ),
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
		$criteria->compare('varname',$this->varname,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('field_type',$this->field_type,true);
		$criteria->compare('field_size',$this->field_size);
		$criteria->compare('field_size_min',$this->field_size_min);
		$criteria->compare('required',$this->required);
		$criteria->compare('match',$this->match,true);
		$criteria->compare('range',$this->range,true);
		$criteria->compare('error_message',$this->error_message,true);
		$criteria->compare('other_validator',$this->other_validator,true);
		$criteria->compare('default',$this->default,true);
		$criteria->compare('widget',$this->widget,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('visible',$this->visible);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => $this->pageSize,
			),
		));
	}
    
    /**
     * @param $value
     * @return formated value (string)
     */
    public function widgetView($model) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'viewAttribute')) {
				return $widgetClass->viewAttribute($model,$this);
			}
		} 
		return false;
    }
    
    public function widgetEdit($model,$params=array()) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'editAttribute')) {
				return $widgetClass->editAttribute($model,$this,$params);
			}
		}
		return false;
    }
	
	public static function getWidgets($fieldType='') {
		$basePath=Yii::getPathOfAlias(Yii::app()->getModule('cart')->widgetPath);
		$widgets = array();
		$list = array(''=>CartModule::t('No'));
		if (self::$_widgets) {
			$widgets = self::$_widgets;
		} else {
			$d = dir($basePath);
			while (false !== ($file = $d->read())) {
				if (strpos($file,'UW')===0) {
					list($className) = explode('.',$file);
					if (class_exists($className)) {
						$widgetClass = new $className;
						if ($widgetClass->init()) {
							$widgets[$className] = $widgetClass->init();
							if ($fieldType) {
								if (in_array($fieldType,$widgets[$className]['fieldType'])) $list[$className] = $widgets[$className]['label'];
							} else {
								$list[$className] = $widgets[$className]['label'];
							}
						}
					}
				}
			}
			$d->close();
		}
		return array($list,$widgets);		
	}
	
	/**
	 * Register Script
	 */
	public function registerManegeScript() {
		$basePath=Yii::getPathOfAlias(Yii::app()->getModule('cart')->assetPath);
		$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCssFile($baseUrl.'/css/redmond/jquery-ui.css');
		$cs->registerCssFile($baseUrl.'/css/style.css');
		$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
		$cs->registerScriptFile($baseUrl.'/js/form.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.json.js');
		
		$widgets = self::getWidgets();
		
		$wgByTypes = ProfileField::itemAlias('field_type');
		foreach ($wgByTypes as $k=>$v) {
			$wgByTypes[$k] = array();
		}
		
		foreach ($widgets[1] as $widget) {
			if (isset($widget['fieldType'])&&count($widget['fieldType'])) {
				foreach($widget['fieldType'] as $type) {
					array_push($wgByTypes[$type],$widget['name']);
				}
			}
		}
		//echo '<pre>'; print_r($widgets[1]); die();
		$js = "

	var name = $('#name'),
	value = $('#value'),
	allFields = $([]).add(name).add(value),
	tips = $('.validateTips');
	
	var listWidgets = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($widgets[0]))."');
	var widgets = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($widgets[1]))."');
	var wgByType = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($wgByTypes))."');
	
	var fieldType = {
			'INTEGER':{
				'hide':['match','other_validator','widgetparams'],
				'val':{
					'field_size':10,
					'default':'0',
					'range':'',
					'widgetparams':''
				}
			},
			'VARCHAR':{
				'hide':['widgetparams'],
				'val':{
					'field_size':255,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'TEXT':{
				'hide':['field_size','range','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'DATE':{
				'hide':['field_size','field_size_min','match','range','widgetparams'],
				'val':{
					'field_size':0,
					'default':'0000-00-00',
					'range':'',
					'widgetparams':''
				}
			},
			'FLOAT':{
				'hide':['match','other_validator','widgetparams'],
				'val':{
					'field_size':'10,2',
					'default':'0.00',
					'range':'',
					'widgetparams':''
				}
			},
			'BOOL':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':0,
					'range':'1==".CartModule::t('Yes').";0==".CartModule::t('No')."',
					'widgetparams':''
				}
			},
			'BLOB':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'BINARY':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			}
		};
			
	function showWidgetList(type) {
		$('div.widget select').empty();
		$('div.widget select').append('<option value=\"\">".CartModule::t('No')."</option>');
		if (wgByType[type]) {
			for (var k in wgByType[type]) {
				$('div.widget select').append('<option value=\"'+wgByType[type][k]+'\">'+widgets[wgByType[type][k]]['label']+'</option>');
			}
		}
	}
		
	function setFields(type) {
		if (fieldType[type]) {
			if (".((isset($_GET['id']))?0:1).") {
				showWidgetList(type);
				$('#widgetlist option:first').attr('selected', 'selected');
			}
			
			$('div.row').addClass('toshow').removeClass('tohide');
			if (fieldType[type].hide.length) $('div.'+fieldType[type].hide.join(', div.')).addClass('tohide').removeClass('toshow');
			if ($('div.widget select').val()) {
				$('div.widgetparams').removeClass('tohide');
			}
			$('div.toshow').show(500);
			$('div.tohide').hide(500);
			".((!isset($_GET['id']))?"
			for (var k in fieldType[type].val) { 
				$('div.'+k+' input').val(fieldType[type].val[k]);
			}":'')."
		}
	}
	
	function isArray(obj) {
		if (obj.constructor.toString().indexOf('Array') == -1)
			return false;
		else
			return true;
	}
		
	$('#dialog-form').dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons: {
			'".CartModule::t('Save')."': function() {
				var wparam = {};
				var fparam = {};
				$('#dialog-form fieldset .wparam').each(function(){
					if ($(this).val()) wparam[$(this).attr('name')] = $(this).val();
				});
				
				var tab = $('#tabs ul li.ui-tabs-selected').text();
				fparam[tab] = {};
				$('#dialog-form fieldset .tab-'+tab).each(function(){
					if ($(this).val()) fparam[tab][$(this).attr('name')] = $(this).val();
				});
				
				if ($.JSON.encode(wparam)!='{}') $('div.widgetparams input').val($.JSON.encode(wparam));
				if ($.JSON.encode(fparam[tab])!='{}') $('div.other_validator input').val($.JSON.encode(fparam)); 
				
				$(this).dialog('close');
			},
			'".CartModule::t('Cancel')."': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});


	$('#widgetparams').focus(function() {
		var widget = widgets[$('#widgetlist').val()];
		var html = '';
		var wparam = ($('div.widgetparams input').val())?$.JSON.decode($('div.widgetparams input').val()):{};
		var fparam = ($('div.other_validator input').val())?$.JSON.decode($('div.other_validator input').val()):{};
		
		// Class params
		for (var k in widget.params) {
			html += '<label for=\"name\">'+((widget.paramsLabels[k])?widget.paramsLabels[k]:k)+'</label>';
			html += '<input type=\"text\" name=\"'+k+'\" id=\"widget_'+k+'\" class=\"text wparam ui-widget-content ui-corner-all\" value=\"'+((wparam[k])?wparam[k]:widget.params[k])+'\" />';
		}
		// Validator params		
		if (widget.other_validator) {
			var tabs = '';
			var li = '';
			for (var t in widget.other_validator) {
				tabs += '<div id=\"tab-'+t+'\" class=\"tab\">';
				li += '<li'+((fparam[t])?' class=\"ui-tabs-selected\"':'')+'><a href=\"#tab-'+t+'\">'+t+'</a></li>';
				
				for (var k in widget.other_validator[t]) {
					tabs += '<label for=\"name\">'+((widget.paramsLabels[k])?widget.paramsLabels[k]:k)+'</label>';
					if (isArray(widget.other_validator[t][k])) {
						tabs += '<select type=\"text\" name=\"'+k+'\" id=\"filter_'+k+'\" class=\"text fparam ui-widget-content ui-corner-all tab-'+t+'\">';
						for (var i in widget.other_validator[t][k]) {
							tabs += '<option value=\"'+widget.other_validator[t][k][i]+'\"'+((fparam[t]&&fparam[t][k])?' selected=\"selected\"':'')+'>'+widget.other_validator[t][k][i]+'</option>';
						}
						tabs += '</select>';
					} else {
						tabs += '<input type=\"text\" name=\"'+k+'\" id=\"filter_'+k+'\" class=\"text fparam ui-widget-content ui-corner-all tab-'+t+'\" value=\"'+((fparam[t]&&fparam[t][k])?fparam[t][k]:widget.other_validator[t][k])+'\" />';
					}
				}
				tabs += '</div>';
			}
			html += '<div id=\"tabs\"><ul>'+li+'</ul>'+tabs+'</div>';
		}
		
		$('#dialog-form fieldset').html(html);
		
		$('#tabs').tabs();
		
		// Show form
		$('#dialog-form').dialog('open');
	});
	
	$('#field_type').change(function() {
		setFields($(this).val());
	});
	
	$('#widgetlist').change(function() {
		if ($(this).val()) {
			$('div.widgetparams').show(500);
		} else {
			$('div.widgetparams').hide(500);
		}
		
	});
	
	// show all function 
	$('div.form p.note').append('<br/><a href=\"#\" id=\"showAll\">".CartModule::t('Show all')."</a>');
 	$('#showAll').click(function(){
		$('div.row').show(500);
		return false;
	});
	
	// init
	setFields($('#field_type').val());
	
	";
		$cs->registerScript(__CLASS__.'#dialog', $js);
	} 
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'field_type' => array(
				'INTEGER' => CartModule::t('INTEGER'),
				'VARCHAR' => CartModule::t('VARCHAR'),
				'TEXT'=> CartModule::t('TEXT'),
				'DATE'=> CartModule::t('DATE'),
				'FLOAT'=> CartModule::t('FLOAT'),
				'BOOL'=> CartModule::t('BOOL'),
				'BLOB'=> CartModule::t('BLOB'),
				'BINARY'=> CartModule::t('BINARY'),
			),
			'required' => array(
				self::REQUIRED_NO => CartModule::t('No'),
				self::REQUIRED_YES => CartModule::t('Yes'),
			),
			'visible' => array(
				self::VISIBLE_ALL => CartModule::t('For all'),
				self::VISIBLE_REGISTER_USER => CartModule::t('Registered users'),
				self::VISIBLE_ONLY_OWNER => CartModule::t('Only owner'),
				self::VISIBLE_NO => CartModule::t('Hidden'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
	
	public function formItem($ProductValue) {
			if ($this->widgetEdit($ProductValue))
				echo $this->widgetEdit($ProductValue);
			elseif ($this->range)
				return CHtml::activeDropDownList($ProductValue,$this->varname,$ProductValue->range($this->range));
			elseif ($this->field_type=="TEXT")
				return CHtml::activeTextArea($ProductValue,$this->varname,array('rows'=>6, 'cols'=>50));
			else
				return CHtml::activeTextField($ProductValue,$this->varname,array('size'=>60,'maxlength'=>(($this->field_size)?$this->field_size:255)));
	}
	
} 