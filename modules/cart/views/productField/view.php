<?php
$this->breadcrumbs=array(
	CartModule::t($model->parentName().' Fields',$model->parentName())=>array('admin'),
	$model->varname,
);

$this->menu=array(
	array('label'=>CartModule::t('Create'), 'url'=>array('create')),
	array('label'=>CartModule::t('Update'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>CartModule::t('Delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>CartModule::t('Are you sure you want to delete this field?'))),
	array('label'=>CartModule::t('Manage '.$model->parentName().' Field',$model->parentName()), 'url'=>array('admin')),
);
?>

<h1><?php echo CartModule::t('View "{varname}"','',array('{varname}'=>$model->varname)); ?></h1>

<?php 

$catalogs = array();
foreach ($model->catalogs as $catalog) {
	array_push($catalogs,CHtml::link($catalog->name,array('catalog/view','id'=>$catalog->id)));
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'varname',
		'title',
		'field_type',
		'field_size',
		'field_size_min',
		'required',
		'match',
		'range',
		'error_message',
		'other_validator',
		'default',
		array(
			'name'=>'catalogs',
			'type'=>'raw',/*
			'value'=>implode(',<br/>',$catalogs),/*/
			'value'=>implode(', ',$catalogs),//*/#
		),
		'widget',
		'widgetparams',
		'position',
		array(
			'name' => 'visible',
			'value' => CDynamicFieldModel::itemAlias("visible",$model->visible),
		),
	),
)); ?>
