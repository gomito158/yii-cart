<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'catalog-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo CartModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pid'); ?>
		<?php echo $form->dropDownList($model,'pid',getTreeSelect($model->id)); ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position'); ?>
		<?php echo $form->error($model,'position'); ?>
	</div><?php
	echo CHtml::activeLabelEx($model,'fields');
	echo getFieldsCheckbox($model,ProductField::model()->findAll(),$model->fields);
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',$model->itemAlias('Status')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? CartModule::t('Create') : CartModule::t('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form --><?php 

function getFieldsCheckbox($model,$fields,$setFields=array(),$ulId='tree') {
	$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$css ='
			/* Catalog Tree */
			#tree {
	    		list-style: none;
				padding-left:1em;
	    	}
			#tree ul {
	    		list-style: none;
				padding-left: 2em;
	    	}
			#tree input {
				margin-right: .5em;
			}
			div.form li label {
				display:inline;
			}
		';
		$js = '
			$(document).ready(function(){
				$("#check_all").click(function(){
					$("#tree input").attr("checked", "checked");
					return false;
				});
				$("#uncheck_all").click(function(){
					$("#tree input").removeAttr("checked");
					return false;
				});
    			
			});
		';
		$cs->registerCss(__CLASS__.'#form', $css);
		$cs->registerScript(__CLASS__.'#form', $js);
	
	$content = "<div class=\"row fields\"><p><a href=\"#\" id=\"check_all\">".CartModule::t('Check All')."</a> | <a href=\"#\" id=\"uncheck_all\">".CartModule::t('Uncheck All')."</a></p><ul id=\"$ulId\">";
	//echo '<pre>'; print_r($model); die();
	if (isset($_POST[get_class($model)]['fields'])) {
		$setFields = $_POST[get_class($model)]['fields'];
	} elseif (count($setFields)) {
		$new = array();
		foreach ($setFields as $item) {
			$new[$item->id] = 1;
		}
		$setFields = $new;
	}
    foreach ($fields as $item) {
    	$htmlOptions = array();
    	if (count($setFields)) {
    		if (isset($setFields[$item->id])&&$setFields[$item->id])
    			$htmlOptions['checked'] = 'checked';
    	}
    	/*
		$htmlOptions['value'] = $item->id;
		
    	if ($model->isNewRecord) {
			if (isset($setCat[$item->id])&&$setCat[$item->id]) 
				$htmlOptions['checked'] = 'checked';
    	} else {
			if (isset($setCat[$item->id]->id))
				$htmlOptions['checked'] = 'checked';
		}//*/
		
    	$content .= '<li>'. CHtml::activeCheckBox($model,"fields[".$item->id."]",$htmlOptions).''.CHtml::activeLabelEx($model,"fields[".$item->id."]",array('label'=>$item->title)).' </li>';
    }
    $content .= '</ul></div>';
    return $content;
}
function getTreeSelect($selfId=0) {
	$tree = array(0=>CartModule::t('No'));
	$items = Catalog::getTree();
	//echo '<pre>'; print_r($items); die();
	$prefix = '';
	foreach ($items as $item) {
		if ($item['self']->id!=$selfId) {
			$tree[$item['self']->id] = $item['self']->name;
			$tree = getChilds($tree,$item['childs'],$selfId);
		}
	}
	
	return $tree;
}

function getChilds($tree,$items,$selfId=0,$prefix='') {
	$prefix .= '--';
	foreach ($items as $item) {
		if ($item['self']->id!=$selfId) {
			$tree[$item['self']->id] = $prefix.' '.$item['self']->name;
			$tree = getChilds($tree,$item['childs'],$selfId,$prefix);
		}
	}
	return $tree;
}



?>