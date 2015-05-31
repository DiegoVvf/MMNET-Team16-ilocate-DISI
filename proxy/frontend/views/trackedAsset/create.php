<?php
$this->breadcrumbs=array(
	'Tracked Assets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TrackedAsset','url'=>array('index')),
	array('label'=>'Manage TrackedAsset','url'=>array('admin')),
);
?>

<h1>Create TrackedAsset</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>