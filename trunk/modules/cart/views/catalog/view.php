<?php
$this->breadcrumbs=array(
	CartModule::t('Catalogs','catalog')=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Catalog','catalog'), 'url'=>array('admin')),
	array('label'=>CartModule::t('Create Catalog','catalog'), 'url'=>array('create')),
	array('label'=>CartModule::t('Update Catalog','catalog'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>CartModule::t('Delete Catalog','catalog'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>CartModule::t('Are you sure you want to delete this catalog?','catalog'))),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php 
$fields = array();
foreach ($model->fields as $field) {
	array_push($fields,CHtml::link($field->title,array('productField/view','id'=>$field->id)));
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		//'pid',
		array(
			'name'=>'pid',
			'type'=>'raw',
			'value'=>(($model->parent)?CHtml::link($model->parent->name,array('view','id'=>$model->parent->id)):CartModule::t('No')),
		),
		'url',
		'name',
		'description',
		array(
			'name'=>'fields',
			'type'=>'raw',
			'value'=>implode(', ',$fields),
		),
		'position',
		array(
			'name' => 'status',
			'value' => $model->itemAlias("Status",$model->status),
		),
	),
)); ?>
