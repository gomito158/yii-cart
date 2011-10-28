<?php
$this->breadcrumbs=array(
	CartModule::t('Catalogs','catalog')=>array('admin'),
	CartModule::t('Create'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Catalog','catalog'), 'url'=>array('admin')),
);
?>

<h1><?php echo CartModule::t('Create Catalog','catalog'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>