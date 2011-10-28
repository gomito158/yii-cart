<?php
$this->breadcrumbs=array(
	CartModule::t('Catalogs','catalog')=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	CartModule::t('Update'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Catalog','catalog'), 'url'=>array('admin')),
	array('label'=>CartModule::t('Create Catalog','catalog'), 'url'=>array('create')),
	array('label'=>CartModule::t('View Catalog','catalog'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo CartModule::t('Update Catalog','catalog').' &laquo;'.$model->name.'&raquo;'; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>