<?php
$this->breadcrumbs=array(
	CartModule::t($model->parentName().' Fields',$model->parentName())=>array('admin'),
	CartModule::t('Manage'),
);

$this->menu=array(
	array('label'=>CartModule::t('Create '.$model->parentName().' Field',$model->parentName()), 'url'=>array('create')),
	array('label'=>CartModule::t('Manage '.$model->parentName(),$model->parentName()), 'url'=>array($model->parentName().'/admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-field-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo CartModule::t('Manage '.$model->parentName().' Fields',$model->parentName()); ?></h1>

<p><?php echo CartModule::t('You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?></p>

<?php echo CHtml::link(CartModule::t('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-field-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'varname',
		array(
			'name'=>'title',
			'value'=>'CartModule::t($data->title,$data->parentName())',
		),
		array(
			'name'=>'required',
			'value'=>'CDynamicFieldModel::itemAlias("required",$data->required)',
			'filter' => CDynamicFieldModel::itemAlias("required"),
		),
		'position',
		array(
			'name'=>'visible',
			'value'=>'CDynamicFieldModel::itemAlias("visible",$data->visible)',
			'filter' => CDynamicFieldModel::itemAlias("visible"),
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
