<?php
$this->breadcrumbs=array(
	'Assets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=> 'Options', 'itemOptions'=>array('class'=>'nav-header')),
	'---',
	array('label'=>'Back to list','url'=>array('index')),
);
?>

<h1>Update Asset - <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>