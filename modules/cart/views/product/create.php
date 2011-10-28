<?php
$this->breadcrumbs=array(
	CartModule::t('Products','product')=>array('admin'),
	CartModule::t('Create'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Products','product'), 'url'=>array('admin')),
);
?>

<h1><?php echo CartModule::t('Create Product','product'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'ProductValue'=>$ProductValue,'newPhoto'=>$newPhoto,'newRelation' => $newRelation,'catalogs'=>$catalogs,'cat_list'=>$cat_list)); ?>