<?php
$this->breadcrumbs=array(
	CartModule::t($model->parentName().' Fields',$model->parentName())=>array('admin'),
	CartModule::t('Create'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage '.$model->parentName().' Field',$model->parentName()), 'url'=>array('admin')),
);
?>

<h1><?php echo CartModule::t('Create '.$model->parentName().' Field',$model->parentName()); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>