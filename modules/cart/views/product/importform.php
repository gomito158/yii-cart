<?php 
$this->breadcrumbs=array(
	CartModule::t('Products','product')=>array('admin'),
	CartModule::t('Import','import'),
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Products','product'), 'url'=>array('admin')),
	array('label'=>CartModule::t('Example CSV file','import'), 'url'=>array('import','examplefile'=>'csv')),
	//array('label'=>CartModule::t('Example XML file','import'), 'url'=>array('import','examplefile'=>'xml')),
);
?>
<h1><?php echo CartModule::t('Import Product','import'); ?></h1>


<?php if(Yii::app()->user->hasFlash('importError')) { ?>
<div><p><?php echo Yii::app()->user->getFlash('importError'); ?></p></div>
<?php } else if (Yii::app()->user->hasFlash('importMessage')) {
echo '<p>'.Yii::app()->user->getFlash('importMessage').'</p>'; ?>
<div id="progressbar"><div id="percent">0 %</div><div id="progress" style="width: 6px;">&nbsp;</div></div>
<a href="#" id="showlog" style="display:none;">Show</a><a href="#" id="hidelog" style="display:none;">Hide</a>
<div id="log">
	<div class="logline last"><?php echo CartModule::t('Analysis of the import file','import'); ?></div>
</div>
<?php 
} else { ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-form',
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo CartModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php
		echo $form->errorSummary($model);
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'importFile');  ?>
		<?php echo $form->fileField($model,'importFile',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'importFile'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(CartModule::t('Import','import')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php } ?>