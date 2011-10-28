<?php
$this->breadcrumbs=array(
	CartModule::t('Products','product')=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>CartModule::t('Manage Products','product'), 'url'=>array('admin')),
	array('label'=>CartModule::t('Create Product','product'), 'url'=>array('create')),
	array('label'=>CartModule::t('Update Product','product'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>CartModule::t('Delete Product','product'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>CartModule::t('Are you sure you want to delete this item?','product'))),
);
?>

<h1><?php echo CartModule::t('View Product','product').' #'.$model->id; ?></h1>

<?php 
$photos = '';
foreach ($model->photos as $photo)
	if (isset($model->photo)&&$model->photo->id!=$photo->id) $photos .= $photo->link(array('w'=>150,'target'=>'_blank'));
				
$attributes = array(
	'id',
	'artno',
	'name',
	array(
		'name' => 'catalogs',
		'type' => 'raw',
		'value' => implode('<br>',catList($model)),
	),
	array(
		'name' => 'photo',
		'type' => 'raw',
		'value' => (($model->photo)?$model->photo->link(array('w'=>300,'target'=>'_blank')):'').'<br />'.$photos,
	),
	array(
		'name'=>'shortdesc',
		'type'=>'raw',
	),
	array(
		'name'=>'fulldesc',
		'type'=>'raw',
	),
	'price',
	'store',
);

$ProductField=$model->value->getFields();
if ($ProductField) {
	foreach($ProductField as $field) {
		if ($field->position>=500) {
			$tt = explode(', ',$model->value->getAttribute($field->varname));
			$stt = '';
			foreach ($tt as $t) {
				if ($stt)
					$stt .= ', '.$model->value->range($field->range,$t);
				else
					$stt = $model->value->range($field->range,$t);
			}
			$field = array(
					'label' => Yii::t("cart", $field->title),
					'name' => $field->varname,
					'value' => $stt,
				);
			if (is_array($field['value'])) $field['value'] = $field['value'][''];
			array_push($attributes,$field);
		} else {
			$field = array(
					'label' => Yii::t("cart", $field->title),
					'name' => $field->varname,
					'value' => (($field->range)?$model->value->range($field->range,$model->value->getAttribute($field->varname)):$model->value->getAttribute($field->varname)),
				);
			if (is_array($field['value'])) $field['value'] = $field['value'][''];
			array_push($attributes,$field);
		}
	}
}
array_push($attributes,
	'url',
	'tags',
	array(
		'name' => 'type',
		'value' => $model->itemAlias("Type",$model->type),
	),
	array(
		'name' => 'status',
		'value' => $model->itemAlias("Status",$model->status),
	)
);

$relation = '';
foreach($model->relation as $n=>$m) { 
	$relation .= '<div style="float:left;text-align:center;margin:3px;padding:5px;background:#fff;">'
		.'<div class="photo">'.(($m->photo)?CHtml::link($m->photo->img(array("w"=>"100")),array('view','id'=>$m->id)):'').'</div>'
		.'<div class="title" style="bottom:55px; text-align:left">'.CHtml::link(CHtml::encode($m->name),array('view','id'=>$m->id)).'</div>'
		.'<div class="pay" style="bottom:30px;">'.str_replace(' ','<i style="padding:0 .1em;"></i>',number_format($m->price,0,',',' ')).'</div>'
	.'</div>';
}

array_push($attributes,
	array(
		'name' => 'relation',
		'type'=>'raw',
		'label'=>CartModule::t('Relation Products','product'),
		'value' => $relation,
	)
);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes,
));


function catList($model) {
	$catalogs = array();
	foreach ($model->catalogs as $catalog)
		array_push($catalogs,CHtml::link($catalog->name,array('catalog/view','id'=>$catalog->id)));
	return $catalogs;
}

?>