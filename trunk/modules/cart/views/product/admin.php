<?php
$this->breadcrumbs=array(
	CartModule::t('Products','product')=>array('admin'),
	CartModule::t('Manage'),
);

$this->menu=array(
	array('label'=>CartModule::t('Create Product','product'), 'url'=>array('create')),
	array('label'=>CartModule::t('Manage Product Field','product'), 'url'=>array('productField/admin')),
	array('label'=>CartModule::t('Manage Catalog'), 'url'=>array('catalog/admin')),
	array('label'=>CartModule::t('Import','import'), 'url'=>array('import')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo CartModule::t('Manage Products','product');?></h1>

<p><?php echo CartModule::t('You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?></p>

<?php echo CHtml::link(CartModule::t('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		//'photo_id',
		'artno',
		//'url',
		//'tags',
		'name',
		//'description',
		//'price',
		array(
			'name'=>'price',
			'value'=>'number_format($data->price,2,".",",")',
			'htmlOptions'=>array('style'=>'text-align:right'),
		),
		'store',
		//'type',
		array(
			'name'=>'status',
			'value'=>'$data->itemAlias("Status",$data->status)',
			'filter' => $model->itemAlias("Status"),
		),
		//*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
