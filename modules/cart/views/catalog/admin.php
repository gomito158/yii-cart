<?php
$this->breadcrumbs=array(
	CartModule::t('Catalogs','catalog')=>array('admin'),
	CartModule::t('Manage'),
);

$this->menu=array(
	array('label'=>CartModule::t('Create Catalog','catalog'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('catalog-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo CartModule::t('Manage Catalogs','catalog');?></h1>

<p><?php echo CartModule::t('You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?></p>

<?php echo CHtml::link(CartModule::t('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'catalog-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'pid',
		'url',
		'name',
		'description',
		'position',
		array(
			'name'=>'status',
			'value'=>'$data->itemAlias("Status",$data->status)',
			'filter' => $model->itemAlias("Status"),
		),
		/*
		'status',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
