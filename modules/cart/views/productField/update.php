<?php
$this->breadcrumbs=array(
	CartModule::t($model->parentName().' Fields',$model->parentName())=>array('admin'),
	$model->varname=>array('view','id'=>$model->id),
	CartModule::t('Update'),
);

$this->menu=array(
	array('label'=>CartModule::t('Create'), 'url'=>array('create')),
	array('label'=>CartModule::t('View'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>CartModule::t('Manage '.$model->parentName().' Field',$model->parentName()), 'url'=>array('admin')),
);
?>

<h1><?php echo CartModule::t('Update "{varname}"','',array('{varname}'=>$model->varname)); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>