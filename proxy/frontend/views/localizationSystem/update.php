<?php
$this->breadcrumbs=array(
	'Localization Systems'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=> 'Options', 'itemOptions'=>array('class'=>'nav-header')),
	'---',
	array('label'=>'Back to list','url'=>array('index')),
);
?>

<h1>Update LocalizationSystem <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>