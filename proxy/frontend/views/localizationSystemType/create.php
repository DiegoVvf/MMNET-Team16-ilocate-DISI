<?php
$this->breadcrumbs=array(
	'Localization System Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LocalizationSystemType','url'=>array('index')),
	array('label'=>'Manage LocalizationSystemType','url'=>array('admin')),
);
?>

<h1>Create LocalizationSystemType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>