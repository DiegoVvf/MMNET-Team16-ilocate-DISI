<?php
$this->breadcrumbs=array(
	'Localization Systems'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LocalizationSystem','url'=>array('index')),
	array('label'=>'Manage LocalizationSystem','url'=>array('admin')),
);
?>

<h1>Create LocalizationSystem</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>