<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'varname'); ?>
		<?php echo $form->textField($model,'varname',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'field_type'); ?>
		<?php echo $form->dropDownList($model,'field_type', array_merge(array(''),CDynamicFieldModel::itemAlias('field_type')) ,array('id'=>'field_type')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'required'); ?>
		<?php echo $form->dropDownList($model,'field_type', CDynamicFieldModel::itemAlias('required')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'error_message'); ?>
		<?php echo $form->textField($model,'error_message',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_validator'); ?>
		<?php echo $form->textField($model,'other_validator',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default'); ?>
		<?php echo $form->textField($model,'default',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'widget'); ?>
		<?php list($widgetsList) = CDynamicFieldModel::getWidgets($model->field_type);
		echo $form->dropDownList($model,'widget',$widgetsList,array('id'=>'widgetlist')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'position'); ?>
		<?php echo $form->textField($model,'position'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'visible'); ?>
		<?php echo $form->dropDownList($model,'visible',CDynamicFieldModel::itemAlias('visible')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(CartModule::t('Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->