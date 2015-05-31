<?php
$this->breadcrumbs=array(
	'Environments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Environment','url'=>array('index')),
	array('label'=>'Manage Environment','url'=>array('admin')),
);
?>

<h1>Create Environment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>