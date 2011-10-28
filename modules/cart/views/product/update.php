<?php
$this->breadcrumbs=array(
	CartModule::t('Products','product')=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	CartModule::t('Update'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Products','product'), 'url'=>array('admin')),
	array('label'=>CartModule::t('Create Product','product'), 'url'=>array('create')),
	array('label'=>CartModule::t('View Product','product'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo CartModule::t('Update Product','product').' #'.$model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'ProductValue'=>$ProductValue,'newPhoto'=>$newPhoto,'newRelation' => $newRelation,'catalogs'=>$catalogs,'cat_list'=>$cat_list)); ?>