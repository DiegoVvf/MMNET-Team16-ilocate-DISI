<?php
	$this->breadcrumbs=array(
		'Environments',
	);
?>

<div class="page-header clearfix">
    <h1>Environments</h1>
    <div class="pull-right">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>Yii::t('labels', 'New environment'),
                'type'=>'primary',
                'icon' => 'icon-plus-sign icon-small icon-white',
                'url' => array('create'),
                'htmlOptions'=>array(
                    // 'class' => 'btn-warning',
                    'id' => 'btn_new',
                ),
            ));
       ?> 
    </div>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'environment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template'=>"{summary} {pager} {items} {pager}",
	'columns'=>array(
		'id',
		'name',
		'deployment' => array(
            'header' => Yii::t('labels', 'Deployment'),
            'name' => 'deployment',
            'type' => 'raw',
            'value' => function ($data, $raw, $column) {
                return $data->deployment->name;
            },
            'htmlOptions'=>array('class'=>'td_people'),
        ),
		array(
			'header' => Yii::t('labels', 'Actions'),
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'updateButtonUrl'=>'Yii::app()->controller->createUrl("update",array("id"=>$data["id"]))',
			'deleteButtonUrl'=>'Yii::app()->controller->createUrl("delete",array("id"=>$data["id"]))',
			'htmlOptions'=>array('class'=>'td_actions'),
		),
	),
)); ?>
