<?php
/* @var $this SiteController */

$this->breadcrumbs=array(
	'Site',
);

$this->menu=array(
	array('label'=> 'Deployments and Environments', 'itemOptions'=>array('class'=>'nav-header')),
	'---',
	array('label'=>'Manage deployments','url'=>array('deployment/index')),
	array('label'=>'Manage environments','url'=>array('environment/index')),
	array('label'=>'Manage localization systems','url'=>array('localizationSystem/index')),
	array('label'=> 'Assets and Tracked Assets', 'itemOptions'=>array('class'=>'nav-header')),
	'---',
	array('label'=>'Manage assets','url'=>array('asset/index')),
	array('label'=>'Manage tracked assets','url'=>array('trackedAsset/index')),
	array('label'=> 'Localization system types and Trackers', 'itemOptions'=>array('class'=>'nav-header')),
	'---',
	array('label'=>'Manage localization system types','url'=>array('localizationSystemType/index')),
	array('label'=>'Manage trackers','url'=>array('tracker/index')),
);
?>

<h1>i-locate</h1>
