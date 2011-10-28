<?php

class ProductController extends Controller
{
	const IMPORT_SESSION_NAME = 'yii-cart-import';
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $defaultAction = 'admin';
	const PAGE_SIZE=30;
	public $importLimit = 1;
	
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','view','delrel','import'),
				'users'=>Yii::app()->getModule('user')->getAdmins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Product;
		$ProductValue=new ProductValue;
		$newPhoto=new Photo;
		
		// ajax validator
		$this->performAjaxValidation($model,$ProductValue,$newPhoto);
		
		$catalogs = Catalog::getTree();
		$cat_list = array();

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			//echo '<pre>'; print_r($_POST); die();
			$ProductValue->attributes=$_POST['ProductValue'];
			$newPhoto->attributes=$_POST['Photo'];
			$ProductValue->pr_id=0;
			$cat_list = $_POST['Product']['catalogs'];
			if($model->validate()&&$ProductValue->validate()&&$newPhoto->validate()) {
				if($model->save()) {
					$newPhoto->filename = CUploadedFile::getInstance($newPhoto,'filename');
					if ($newPhoto->filename) {
						$newPhoto->setDefault();
						$newPhoto->title = $model->name;
						if ($newPhoto->save()) {
							$newPhoto->fullSave();
							if(!$model->photo_id) {
								$model->photo_id = $newPhoto->id;
								$model->save();
							}
						}
					}
					$ProductValue->pr_id=$model->id;
					$ProductValue->save();
					if ($newPhoto->id) {
						$photoRel = new PhotoRelation;
						$photoRel->item_id = $model->id;
						$photoRel->photo_id = $newPhoto->id;
						$photoRel->save();
					}
				}
				
				// Catalog relation
				foreach ($_POST['Product']['catalogs'] as $i=>$item) {
					if ($item) {
						$catRel = new CatalogRelation;
						$catRel->cid = $i;
						$catRel->pr_id = $model->id;
						$catRel->save();
					}
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		
		$newRelation = new ProductRelation;
		$this->render('create',array(
			'model'=>$model,
			'ProductValue'=>$ProductValue,
			'newPhoto'=>$newPhoto,
			'catalogs'=>$catalogs,
			'cat_list'=>$cat_list,
			'newRelation' => $newRelation,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		$newRelation = new ProductRelation;
		$ProductValue=$model->value;
		$newPhoto=new Photo;
		
		// ajax validator
		$this->performAjaxValidation($model,$ProductValue,$newPhoto);
		
		$catalogs = Catalog::getTree();
		$cat_list = array();
		
		foreach ($model->catalogs as $item)
			$cat_list[$item->id] = $item;
			
		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			$ProductValue->attributes=$_POST['ProductValue'];
			$newPhoto->attributes=$_POST['Photo'];
			$newRelation->attributes=$_POST['ProductRelation'];
			
			if($model->validate()&&$ProductValue->validate()&&$newPhoto->validate()) {
				
				// Update photo title (if set)
				/*
				if ($model->photo) {
					if ($model->photo->title!=$model->name) {
						$model->photo->title = $model->name;
						$model->photo->save();
					}
				}//*/
				
				// Upload new photo
				$newPhoto->filename = CUploadedFile::getInstance($newPhoto,'filename');
				if ($newPhoto->filename) {
					$newPhoto->setDefault();
					$newPhoto->title = $model->name;
					if ($newPhoto->save()) {
						$newPhoto->fullSave();
						if(!$model->photo_id) $model->photo_id = $newPhoto->id;
					}
				}
				
				// Save product model
				if ($model->save()) {
					$new_attr = $ProductValue->attributes;
					foreach ($new_attr as $name=>$fieldarr)
					{
						if (is_array($fieldarr)) {
							$new_attr[$name] = implode(', ',$fieldarr);
						}
					}
					$ProductValue->attributes = $new_attr;
					// Save product value
					$ProductValue->save();
					
					// Add relation to new photo
					if ($newPhoto->id) {
						$photoRel = new PhotoRelation;
						$photoRel->item_id = $model->id;
						$photoRel->photo_id = $newPhoto->id;
						$photoRel->save();
					}
				}
				
				// Catalog relation
				foreach ($_POST['Product']['catalogs'] as $i=>$item) {
					if ($item) {
						if (!isset($cat_list[$i])) {
							$catRel = new CatalogRelation;
							$catRel->cid = $i;
							$catRel->pr_id = $model->id;
							$catRel->save();
						}	
					} else {
						if (isset($cat_list[$i])) {
							CatalogRelation::model()->find(array(
								'condition'=>'cid=:cid AND pr_id=:pr_id',
								'params'=>array(':cid'=>$i,':pr_id'=>$model->id),
							))->delete();
						}
					}
				}
				if ($newRelation->pr_id) {
					$relations = explode(',',$newRelation->pr_id);
					foreach ($relations as $rel) {
						$pr_id = (int)trim($rel);
						if ($pr_id) {
							$newRelation = new ProductRelation;
							$newRelation->pr_id = $pr_id;
							$newRelation->pid = $model->id;
							$newRelation->save();
						}
					}
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'ProductValue'=>$ProductValue,
			'newPhoto'=>$newPhoto,
			'catalogs'=>$catalogs,
			'cat_list'=>$cat_list,
			'newRelation' => $newRelation,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelrel()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$relation = ProductRelation::model()->findByAttributes(array('pid'=>$_GET['pid'],'pr_id'=>$_GET['pr_id']));
			$relation->delete();
			
			#TODO: Delete photo and relation
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('update','id'=>$_GET['id']));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionImport() {
		$model = new ImportForm;
		
		if (isset($_GET['examplefile'])) {
			$product = new Product;
			$importFields = $product->attributeLabels();
			$value = new ProductValue;
			foreach ($value->getFields() as $field)
				$importFields[$field->varname] = $field->title;
			
			if ($_GET['examplefile']=='xml') {
				//header('Content-Disposition: attachment; filename="examplefile.xml"');
			} else {
				header('Content-Disposition: attachment; filename="examplefile.csv"');
				$content = implode(';',array_values($importFields))."\n";
				$content .= implode(';',array_keys($importFields))."\n";
				if (isset(Yii::app()->getModule('cart')->importCSVCharset)&&Yii::app()->getModule('cart')->importCSVCharset!='UTF-8')
					$content = iconv("UTF-8",Yii::app()->getModule('cart')->importCSVCharset,$content);
				echo $content;
			}
			Yii::app()->end();
		}
		if (isset($_POST['ajax'])) {
			$session = Yii::app()->session[self::IMPORT_SESSION_NAME];
			if ($_POST['line']<$session['count']) {
				
				// Import product
				//echo '<pre>'; print_r(array($session,$_POST)); die();
				$content = explode("\n",file_get_contents($session['filename']));
				if (isset($content[$_POST['line']])) {
					echo CJSON::encode($this->productImport(CJSON::decode($content[$_POST['line']])));
				} else {
					echo CJSON::encode(array('message'=>$session['filename'].' - Error data line '.$_POST['line']));
				}
			} else {
				echo CJSON::encode(array('message'=>'Finished!'));
			}
			//sleep(1);
			Yii::app()->end();//*/
		}
		if(isset($_POST['ImportForm'])) {
			$model->attributes=$_POST['ImportForm'];
			if($model->validate()) {
				$model->importFile=CUploadedFile::getInstance($model,'importFile');
				$content = file_get_contents($model->importFile->tempName);
				if (isset(Yii::app()->getModule('cart')->importCSVCharset)&&Yii::app()->getModule('cart')->importCSVCharset!='UTF-8')
					$content = iconv(Yii::app()->getModule('cart')->importCSVCharset,"UTF-8",$content);
					
				$fields = array();
				$array = array();
				$content = explode("\n",$content);
				$fields = explode(';',$content[1]);
				foreach ($content as $i=>$line) {
					$line = trim($line);
					if ($line&&$i>1) {
						$line = explode(';',$line);
						$item = array();
						foreach ($fields as $n=>$field) {
							$item[trim($field)] = trim($line[$n]);
						}
						if ($item) {
							$item = CJSON::encode($item);
							array_push($array,$item);
						}
					}
				}
				$session = array(
					'line'=>0,
					'count'=>count($array),
					'filename' => Yii::app()->getModule('cart')->importPathPhotos.'/yii-cart-import_'.date('Y-m-d_H-i-s').'.import',
				);
				if ($fh = fopen($session['filename'], 'w+')) {
					fwrite($fh, implode("\n",$array));
					fclose($fh);
					Yii::app()->session[self::IMPORT_SESSION_NAME] = $session;
					Yii::app()->user->setFlash('importMessage',CartModule::t('Import {count} products','import',array('{count}'=>count($array))));
					
					$cs = Yii::app()->getClientScript();
					$cs->registerCoreScript('jquery');
					$css ='
						/* progress bar */
						#progressbar {
							margin: 10px 0;
							padding:4px;
							border:1px solid #6FACCF;
							position:relative;
							-moz-border-radius: 5px;-webkit-border-radius:5px;
						}
						#progressbar #percent {
							position:absolute;
							left:0;
							right:0;
							text-align:center;
							color:#0066A4;
							font-weight:bold;
						}
						#progressbar #progress {
							background: #EFFDFF;
							border:1px solid #B7D6E7;
							margin:-1px;
							-moz-border-radius: 3px;-webkit-border-radius:3px;
						}
						#log {
							position:relative;
							overflow: auto;
							max-height: 300px;
						}
						#log .logline {
							position:absolute;
							padding: 5px;
							border: 1px solid #fff;
							-moz-border-radius: 5px;-webkit-border-radius:5px;
						}
						#log .logline:hover {
							border: 1px solid #B7D6E7
						}
						#log .tohide {
							color:#298DCD;/*#B7D6E7*/
						}
						#log .error {color:#f00;}
						#log .addnew {color:#298DCD;}
						#log span.warning {color:#f00;}
						#log span.note {color:#ff9900;}
					';
					$js = '
						$(document).ready(function(){
							$("#showlog").click(function(){
								$("#log div.logline").attr("style","position:relative;display:none;").show(500);
								$(this).hide();
								$("#hidelog").show();
							});
							$("#hidelog").click(function(){
								$("#log div.logline").hide(500);
								$(this).hide();
								$("#showlog").show();
							});
							
			    			var i = 0;
			    			var size = '.count($array).';
			    			
			    			getData();
			    			
			    			function getData() {
			    				var AjaxError = false;
			    				$.ajax({
			    					async: false,
			    					type: \'POST\',
			    					data: ({ajax : 1,line: i}),
			    					dataType:\'json\',
			    					url:\''.$this->createUrl('product/import').'\',
			    					success: function(data) {
			    						if (data!=null) {
		    								addLogLine(data.message,data.error,data.warning,data.note,data.new);
				    						i++;
				    						setProgress(i,size);
				    					} else {
			    							addLogLine("{url: '.$this->createUrl('product/import').', data: {ajax : 1,line: "+i+"}, return: null}");
			    							addLogLine("'.CartModule::t('Import error!\nPlease contact your administrator!','import').'");
				    						AjaxError = true;
										}
									},
									error: function() {
		    							addLogLine("{url: '.$this->createUrl('product/import').', data: {ajax : 1,line: "+i+"}}");
		    							addLogLine("'.CartModule::t('Import error!\nPlease contact your administrator!','import').'");
					    				AjaxError = true;
									},
								});
								if (AjaxError) {
									alert("'.CartModule::t('Import error!\nPlease contact your administrator!','import').'");
				    				$("#showlog").show();
								} else {
				    				if (i<size) {
				    					'.((Yii::app()->getModule('cart')->importAjaxSleep)?'sleep('.Yii::app()->getModule('cart')->importAjaxSleep.');':'').'
				    					getData();
				    				} else {
				    					$("#showlog").show();
									}
								}
			    			}
			    			
			    			function setProgress(n,c) {
			    				var p = parseInt(100/c*n);
			    				$("#percent").text(p+" %");
			    				//$("#progress").animate({width: p+"%"});
			    				$("#progress").width(p+"%");
			    			}
			    			
			    			function addLogLine(line,er,wr,nt,nw) {
			    				$("#log div.last").removeClass("last").addClass("logline").hide();
			    				$("#log").append("<div class=\"last "+((nw)?"addnew":"update")+((er)?" error":"")+"\">"+((wr)?"<span class=\"warning\">"+wr+"</span><br/>":"")+((nt)?"<span class=\"note\">"+nt+"</span><br/>":"")+line+"</div>");
			    			}
			    			function sleep(milliseconds) {
							  var start = new Date().getTime();
							  for (var i = 0; i < 1e7; i++) {
							    if ((new Date().getTime() - start) > milliseconds){
							      break;
							    }
							  }
							}	    			
						});
					';
					$cs->registerCss(__CLASS__.'#form', $css);
					$cs->registerScript(__CLASS__.'#form', $js);
					
				} else {
					Yii::app()->user->setFlash('importError',CartModule::t('Failed to create file: {filename}','import',array('{filename}'=>$session['filename'])));
				}
			}
		}
		$this->render('importform',array(
			'model'=>$model,
		));
	}
	
	function productImport($array) {
		if ($array['id']||$array['artno']) {
			$log = array('error'=>0,'warning'=>'','note'=>'');
			if ($array['id']) {
				$product = $this->loadModel($array['id']);
			} else {
				$product = Product::model()->findByAttributes(array('artno'=>$array['artno']));
			}
			if ($product) {
				$value = $product->value;
				$log['new'] = 0;
			} else {
				$log['new'] = 1;
				$product = new Product;
				$value=new ProductValue;
			}
			
			foreach ($array as $f=>$v) {
				if (array_key_exists($f,$product->attributes)) {
					if (in_array($f,array('id','photo_id','price','store','type','status'))) {
						if ($f!='photo_id'&&$f!='id') {
							$product->setAttribute($f,(int)$v);
						}
					} else {
						$product->setAttribute($f,$v);
					}
				} elseif (array_key_exists($f,$value->attributes)) {
					$value->setAttribute($f,$v);
				}
			}
			
			if ($product->validate()&&$value->validate()) {
				if ($log['new']) {
					$product->save();
					$value->pr_id = $product->id;
				}
				if (isset($array['catalogs'])) {
					$catalogs = explode('|',$array['catalogs']);
					foreach ($catalogs as $branch) {
						$parent_id = 0;
						foreach (explode('=>',$branch) as $cat_name) {
							$catalog = Catalog::model()->findByAttributes(array('name'=>$cat_name,'pid'=>$parent_id));
							if ($catalog) {
								$carRel = new CatalogRelation;
								$carRel->cid = $parent_id = $catalog->id;
								$carRel->pr_id = $product->id;
								$carRel->save();
							} else {
								$log['note'] = CartModule::t('Not found catalog "{catalog}" in branch "{branch}".','import',array('{branch}'=>$branch,'{catalog}'=>$cat_name));
							}
						}
					}
				}
				if ($array['photo']) {
					$photos = explode('|',$array['photo']);
					foreach ($photos as $photo) {
						$path = Yii::app()->getModule('cart')->importPathPhotos.'/'.$photo;
						$newPhoto=new Photo;
						if ($newPhoto->addByFile($path,$product->id)) {
							if (!$product->photo_id&&$newPhoto->id) {
								$product->photo_id = $newPhoto->id;
								$product->save();
							}
						} else {
							$log['warning'] = CartModule::t('Failed to add photo: {photo}','import',array('{photo}'=>$photo));
						}
					}
				}
				
				$value->save();
				$product->save();
				
				if ($log['new'])
					$log['message'] = CartModule::t('Addded product name (artno)','import',$array);
				else
					$log['message'] = CartModule::t('Changed product name (artno)','import',$array);
				
			} else {
				$log['error'] = 1;
				if (!$product->validate())
					$log['message'] = CartModule::t('Product validation error','import').' ('.CHtml::errorSummary($product).')<br/>';
				if (!$value->validate())
					$log['message'] = CartModule::t('Product fields validation error','import').' ('.CHtml::errorSummary($value).')';
			}
			
			return $log;
		} else return array('message'=>CartModule::t('Not found the ID and Product Marking','import'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model,$ProductValue,$newPhoto)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			$rez = array();
			$rez = array_merge($rez,CJavaScript::jsonDecode(CActiveForm::validate($model)));
			$rez = array_merge($rez,CJavaScript::jsonDecode(CActiveForm::validate($ProductValue)));
			$rez = array_merge($rez,CJavaScript::jsonDecode(CActiveForm::validate($newPhoto)));
			echo CJavaScript::jsonEncode($rez);
			Yii::app()->end();
		}
	}
}
