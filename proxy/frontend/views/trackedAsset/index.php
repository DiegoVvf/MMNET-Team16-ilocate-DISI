<?php
	$this->breadcrumbs=array(
		'Tracked Asset',
	);
?>

<div class="page-header clearfix">
    <h1>Tracked Asset</h1>
    <div class="pull-right">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>Yii::t('labels', 'New tracked asset'),
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
		'asset' => array(
            'header' => Yii::t('labels', 'Asset'),
            'name' => 'id_asset',
            'type' => 'raw',
            'value' => function ($data, $raw, $column) {
                return $data->idAsset->name;
            },
            'htmlOptions'=>array('class'=>'td_people'),
        ),
        'deployment' => array(
            'header' => Yii::t('labels', 'Deployment'),
            'name' => 'id_deployment',
            'type' => 'raw',
            'value' => function ($data, $raw, $column) {
                return $data->idDeployment->name;
            },
            'htmlOptions'=>array('class'=>'td_people'),
        ),
        'environment' => array(
            'header' => Yii::t('labels', 'Environment'),
            'name' => 'id_environment',
            'type' => 'raw',
            'value' => function ($data, $raw, $column) {
                return $data->idEnvironment->name;
            },
            'htmlOptions'=>array('class'=>'td_people'),
        ),
        'tracker' => array(
            'header' => Yii::t('labels', 'Tracker'),
            'name' => 'id_tracker',
            'type' => 'raw',
            'value' => function ($data, $raw, $column) {
                return $data->idTracker->id_localization_tag;
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
