<?php
$this->breadcrumbs=array(
	'Deployments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Deployment','url'=>array('index')),
	array('label'=>'Manage Deployment','url'=>array('admin')),
);
?>

<h1>Create Deployment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>