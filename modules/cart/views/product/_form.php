<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo CartModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php
		echo $form->errorSummary($model);
		echo $form->errorSummary($ProductValue);
		echo $form->errorSummary($newPhoto);
		echo $form->errorSummary($newRelation);
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'artno'); ?>
		<?php echo $form->textField($model,'artno',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'artno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shortdesc'); ?>
		<?php echo $form->textArea($model,'shortdesc',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'shortdesc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fulldesc'); ?>
		<?php echo $form->textArea($model,'fulldesc',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'fulldesc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'store'); ?>
		<?php echo $form->textField($model,'store'); ?>
		<?php echo $form->error($model,'store'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',$model->itemAlias('Type')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>


<?php 
		echo CHtml::activeLabelEx($model,'catalogs');
		echo getTreeCheckbox($model,$catalogs,$cat_list);
	
		echo $form->hiddenField($ProductValue,'pr_id');
		$ProductField=$ProductValue->getFields();
		if ($ProductField) {
			foreach($ProductField as $field) {
			?>
	<div class="row fields <?php echo $field->varname;?>">
		<?php
			echo $form->labelEx($ProductValue,$field->varname);
			echo $field->formItem($ProductValue);
			echo $form->error($ProductValue,$field->varname);
		?>
	</div>	
		<?php
			}
		}
?>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',$model->itemAlias('Status')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

<?php
	/*
	foreach($catalogs as $catalog): ?>
	<div class="row">
		<?php 
		
		$htmlOptions = array();
		if ($model->isNewRecord)
			if (isset($cat_list[$catalog->id])&&$cat_list[$catalog->id]) $htmlOptions['checked'] = 'checked';
		else
			if (isset($cat_list[$catalog->id]->id)) $htmlOptions['checked'] = 'checked';
		echo $form->checkBox($model,"catalogs[$catalog->id]",$htmlOptions).' '.$catalog->name;
		
		?>
	</div>
	<?php 
	endforeach;
	//*/
	if ($model->photos) {
		echo CHtml::activeLabelEx($model,'photo_id',array('label'=>CartModule::t('Default photo','product'))); 
		?>
	<div id="photos">
		<?php
		foreach($model->photos as $photo): ?>
		<div class="row">
			<?php echo $form->radioButton($model,'photo_id',array('value'=>$photo->id,'uncheckValue'=>NULL)); ?>	
			<?php echo $photo->link(array('w'=>200,'target'=>'_blank')); ?>
			<?php echo CHtml::linkButton(Yii::t("cart", 'delete'),array('submit'=>array('photo/safedelete','id'=>$photo->id,'item_id'=>$model->id),'confirm'=>Yii::t("cart", 'Are you sure to delete this photo?'))); ?>
		</div>
		<?php endforeach;
	?>
	</div><?php 
	}
   ?>
	<div class="row">
		<?php echo $form->labelEx($newPhoto,'filename',array('label'=>CartModule::t('Add photo','product')));  ?>
		<?php echo $form->fileField($newPhoto,'filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($newPhoto,'filename'); ?>
	</div>
	
	<?php if ($model->id) {?>
	<h3><?php echo CartModule::t('Add Relation','product'); ?></h3>
	<div class="row">
		<?php echo CHtml::activeLabelEx($newRelation,'pr_id',array('label'=>CartModule::t('Product Id','product'))); ?>
		<?php echo CHtml::activeTextField($newRelation,'pr_id'); ?>
		<?php echo CHtml::error($newRelation,'pr_id'); ?>
	</div>
	<?php } 
	
	if ($model->relation) echo '<h2>'.CartModule::t('Relation Products','product').'</h2>';
	//echo "<pre>"; print_r($model->relation); die();
	foreach($model->relation as $n=>$m): ?>
		<div class="row">
			<div class="photo"><?php if ($m->photo) echo CHtml::link($m->photo->img(array("w"=>"100")),array('view','id'=>$m->id)); ?></div>
			<div class="title" style="bottom:55px; text-align:left"><?php echo CHtml::encode($m->name); ?></div>
			<div class="pay" style="bottom:30px;"><?php echo str_replace(' ','<i style="padding:0 .1em;"></i>',number_format($m->price,0,',',' ')); ?></div>
			<div><?php echo CHtml::linkButton(CartModule::t('Delete Relation','product'),array('submit'=>array('delrel','pid'=>$model->id,'pr_id'=>$m->id),'confirm'=>CartModule::t('Are you sure to delete this relation?','product'))); ?></div>
		</div>
	<?php 
	endforeach;
		
	?>
	


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? CartModule::t('Create') : CartModule::t('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form --><?php 

    
    function getTreeCheckbox($model,$catalogs,$setCat=array(),$ulId='tree') {
    	$cats = Catalog::model()->with('fields')->findAll();
    	$js_cats_list = array();
    	
    	foreach ($cats as $cat) {
    		$fields = array();
    		foreach ($cat->fields as $field) {
    			array_push($fields,$field->varname);
    		}
    		array_push($js_cats_list,$cat->id.':[\''.implode('\',\'',$fields).'\']');
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
				var cats_list = {'.implode(',',$js_cats_list).'};
				
				$("#tree input").click(function(){
					if (this.checked) {
						$(this).parents("li").children("input").attr("checked", "checked");
					} else {
						$(this).parent("li").find("input").removeAttr("checked");
					}
					showFields();
    			});
    			showFields();
    			
    			function showFields() {
    				$(".fields").addClass(\'tohide\').removeClass(\'toshow\');
    				
    				$("#tree input:checked").each(function(){
    					var cat_id = $(this).attr(\'id\').replace(\'Product_catalogs_\',\'\');
    					if (cats_list[cat_id]) {
    						for (var k in cats_list[cat_id]) {
    							$(\'.\'+cats_list[cat_id][k]).addClass(\'toshow\').removeClass(\'tohide\')
    						}
    					}
    				});
					
					$(\'div.toshow\').show(500);
					$(\'div.tohide\').hide(500);
    			}
			});
		';
		$cs->registerCss(__CLASS__.'#form', $css);
		$cs->registerScript(__CLASS__.'#form', $js);
		
		
    	$content = "<ul id=\"$ulId\">";
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
    	$content .= '</ul>';
    	
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