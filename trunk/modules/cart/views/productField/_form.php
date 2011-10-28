<?php ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-field-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo CartModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row varname">
		<?php echo $form->labelEx($model,'varname'); ?>
		<?php echo (($model->id)?$form->textField($model,'varname',array('size'=>60,'maxlength'=>50,'readonly'=>true)):$form->textField($model,'varname',array('size'=>60,'maxlength'=>50))); ?>
		<?php echo $form->error($model,'varname'); ?>
		<p class="hint"><?php echo CartModule::t("Allowed lowercase letters and digits."); ?></p>
	</div>

	<div class="row title">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
		<p class="hint"><?php echo CartModule::t('Field name on the language of "sourceLanguage".'); ?></p>
	</div>

	<div class="row field_type">
		<?php echo $form->labelEx($model,'field_type'); ?>
		<?php echo (($model->id)?$form->textField($model,'field_type',array('size'=>60,'maxlength'=>50,'readonly'=>true,'id'=>'field_type')):$form->dropDownList($model,'field_type',CDynamicFieldModel::itemAlias('field_type'),array('id'=>'field_type'))); ?>
		<?php echo $form->error($model,'field_type'); ?>
		<p class="hint"><?php echo CartModule::t('Field type column in the database.'); ?></p>
	</div>

	<div class="row field_size">
		<?php echo $form->labelEx($model,'field_size'); ?>
		<?php echo (($model->id)?$form->textField($model,'field_size',array('readonly'=>true)):$form->textField($model,'field_size')); ?>
		<?php echo $form->error($model,'field_size'); ?>
		<p class="hint"><?php echo CartModule::t('Field size column in the database.'); ?></p>
	</div>

	<div class="row field_size_min">
		<?php echo $form->labelEx($model,'field_size_min'); ?>
		<?php echo $form->textField($model,'field_size_min'); ?>
		<?php echo $form->error($model,'field_size_min'); ?>
		<p class="hint"><?php echo CartModule::t('The minimum value of the field (form validator).'); ?></p>
	</div>

	<div class="row required">
		<?php echo $form->labelEx($model,'required'); ?>
		<?php echo $form->dropDownList($model,'required', CDynamicFieldModel::itemAlias('required')); ?>
		<?php echo $form->error($model,'required'); ?>
		<p class="hint"><?php echo CartModule::t('Required field (form validator).'); ?></p>
	</div>

	<div class="row match">
		<?php echo $form->labelEx($model,'match'); ?>
		<?php echo $form->textField($model,'match',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'match'); ?>
		<p class="hint"><?php echo CartModule::t("Regular expression (example: '/^[A-Za-z0-9\s,]+$/u')."); ?></p>
	</div>

	<div class="row range">
		<?php echo $form->labelEx($model,'range'); ?>
		<?php echo $form->textField($model,'range',array('size'=>60,'maxlength'=>5000)); ?>
		<?php echo $form->error($model,'range'); ?>
		<p class="hint"><?php echo CartModule::t('Predefined values (example: 1;2;3;4;5 or 1==One;2==Two;3==Three;4==Four;5==Five).'); ?></p>
	</div>

	<div class="row error_message">
		<?php echo $form->labelEx($model,'error_message'); ?>
		<?php echo $form->textField($model,'error_message',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'error_message'); ?>
		<p class="hint"><?php echo CartModule::t('Error message when you validate the form.'); ?></p>
	</div>

	<div class="row other_validator">
		<?php echo $form->labelEx($model,'other_validator'); ?>
		<?php echo $form->textField($model,'other_validator',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'other_validator'); ?>
		<p class="hint"><?php echo CartModule::t('JSON string (example: {example}).','',array('{example}'=>CJavaScript::jsonEncode(array('file'=>array('types'=>'jpg, gif, png'))))); ?></p>
	</div>

	<div class="row default">
		<?php echo $form->labelEx($model,'default'); ?>
		<?php echo (($model->id)?$form->textField($model,'default',array('size'=>60,'maxlength'=>255,'readonly'=>true)):$form->textField($model,'default',array('size'=>60,'maxlength'=>255))); ?>
		<?php echo $form->error($model,'default'); ?>
		<p class="hint"><?php echo CartModule::t('The value of the default field (database).'); ?></p>
	</div><?php
	echo CHtml::activeLabelEx($model,'catalogs');
	echo getTreeCheckbox($model,Catalog::getTree(),$model->catalogs);
	?>

	<div class="row widget">
		<?php echo $form->labelEx($model,'widget'); ?>
		<?php 
		list($widgetsList) = CDynamicFieldModel::getWidgets($model->field_type);
		echo $form->dropDownList($model,'widget',$widgetsList,array('id'=>'widgetlist'));
		//echo $form->textField($model,'widget',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo CHtml::error($model,'widget'); ?>
		<p class="hint"><?php echo CartModule::t('Widget name.'); ?></p>
	</div>

	<div class="row widgetparams">
		<?php echo $form->labelEx($model,'widgetparams'); ?>
		<?php echo $form->textField($model,'widgetparams',array('size'=>60,'maxlength'=>5000,'id'=>'widgetparams')); ?>
		<?php echo $form->error($model,'widgetparams'); ?>
		<p class="hint"><?php echo CartModule::t('JSON string (example: {example}).','',array('{example}'=>CJavaScript::jsonEncode(array('param1'=>array('val1','val2'),'param2'=>array('k1'=>'v1','k2'=>'v2'))))); ?></p>
	</div>

	<div class="row position">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position'); ?>
		<?php echo $form->error($model,'position'); ?>
		<p class="hint"><?php echo CartModule::t('Display order of fields.'); ?></p>
	</div>

	<div class="row visible">
		<?php echo $form->labelEx($model,'visible'); ?>
		<?php echo $form->dropDownList($model,'visible',CDynamicFieldModel::itemAlias('visible')); ?>
		<?php echo $form->error($model,'visible'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? CartModule::t('Create') : CartModule::t('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div id="dialog-form" title="<?php echo CartModule::t('Widget parametrs'); ?>">
	<form>
	<fieldset>
	</fieldset>
	</form>
</div><?php 

    
    function getTreeCheckbox($model,$catalogs,$setCatalogs=array(),$ulId='tree') {
    	$setCat = array();
    	foreach ($setCatalogs as $catalog) {
    		$setCat[$catalog->id] = $catalog;
    	}
		$basePath=Yii::getPathOfAlias('application.modules.cart.views.asset');
		$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
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
				$("#tree input").click(function(){
					if (this.checked) {
						$(this).parent("li").find("input").attr("checked", "checked");
					}
    			});
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
		
		
    	$content = "<div class=\"row catalogs\"><p><a href=\"#\" id=\"check_all\">".CartModule::t('Check All')."</a> | <a href=\"#\" id=\"uncheck_all\">".CartModule::t('Uncheck All')."</a></p><ul id=\"$ulId\">";
    	foreach ($catalogs as $item) {
    		$htmlOptions = array();
			$htmlOptions['value'] = $item['self']->id;
			
			
			
    		if ($model->isNewRecord) {
				if (isset($setCat[$item['self']->id])&&$setCat[$item['self']->id]) 
					$htmlOptions['checked'] = 'checked';
    		} else {
				if (isset($setCat[$item['self']->id]->id))
					$htmlOptions['checked'] = 'checked';
			}
			
    		$content .= '<li>'. CHtml::activeCheckBox($model,"catalogs[".$item['self']->id."]",$htmlOptions).''.CHtml::activeLabelEx($model,"catalogs[".$item['self']->id."]",array('label'=>$item['self']->name)).getTreeCheckboxChilds($model,$item['childs'],$setCat).' </li>';
    	}
    	$content .= '</ul></div>';
    	
    	return $content;
    }
    
    function getTreeCheckboxChilds($model,$catalogs,$setCat){
    	if (count($catalogs)) {
	    	$content = "<ul>";
	    	foreach ($catalogs as $item) {
	    		$htmlOptions = array();
		    	if ($model->isNewRecord) {
					if (isset($setCat[$item['self']->id])&&$setCat[$item['self']->id]) 
						$htmlOptions['checked'] = 'checked';
	    		} else {
					if (isset($setCat[$item['self']->id]->id))
						$htmlOptions['checked'] = 'checked';
				}
				
	    		$content .= '<li>'. CHtml::activeCheckBox($model,"catalogs[".$item['self']->id."]",$htmlOptions).''.CHtml::activeLabelEx($model,"catalogs[".$item['self']->id."]",array('label'=>$item['self']->name)).getTreeCheckboxChilds($model,$item['childs'],$setCat).'</li>';
	    	}
	    	$content .= '</ul>';
	    	
	    	return $content;
    	} else return '';
    }
?>