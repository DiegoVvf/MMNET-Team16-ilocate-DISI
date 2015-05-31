<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<!-- Use the .htaccess and remove these lines to avoid edge case issues. More info: h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo h($this->pageTitle); /* using shortcut for CHtml::encode */ ?></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1">


	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css">
	<!--using less instead? file not included-->
	<!--<link rel="stylesheet/less" type="text/css" href="/less/styles.less">-->

	<!--<script src="/less/less-1.3.0.min.js"></script>-->
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('zone/scripts', array('name' => '/js/utils.js'))); ?>
</head>

<body>
<div class="container" id="page">
	<?php 
		$this->widget('bootstrap.widgets.TbNavbar', array(
			'type' => null, // null or 'inverse'
			'brand' => CHtml::encode(Yii::app()->name),
			'brandUrl' => Yii::app()->request->getBaseUrl(true),
			'collapse' => true, // requires bootstrap-responsive.css
			'items' => array(
				array(
					'class' => 'bootstrap.widgets.TbMenu',
					'htmlOptions' => array('class' => 'pull-right'),
					'items' => array(
						// array('label' => 'Home', 'url' => array('/site/index')),
						array('label' => 'Deployments & Environments', 'icon' => null, 'url' => '#', 'items' => array(
				            array('label' => Yii::t('labels', 'Manage Deployments'), 'url' => array('/deployment/index')),
							'---',
							array('label' => Yii::t('labels', 'Manage Environments'), 'url' => array('/environment/index')),
							'---',
							array('label' => Yii::t('labels', 'Manage Localization Systems'), 'url' => array('/localizationSystem/index'))
						)),
						array('label' => 'Assets and Traked Assets', 'icon' => null, 'url' => '#', 'items' => array(
				            array('label' => Yii::t('labels', 'Manage Assets'), 'url' => array('/asset/index')),
							'---',
							array('label' => Yii::t('labels', 'Manage Traked Assets'), 'url' => array('/trackedAsset/index'))
						)),
						array('label' => 'Localization System Types and Trackers', 'icon' => null, 'url' => '#', 'items' => array(
				            array('label' => Yii::t('labels', 'Manage Localization Systems'), 'url' => array('/localizationSystemType/index')),
							'---',
							array('label' => Yii::t('labels', 'Trackers'), 'url' => array('/tracker/index'))
						)),

						// array('label' => 'Customers', 'url' => array('/customer/index'),
						// 	'active' => (Yii::app()->request->getParam('customer') || in_array(Yii::app()->controller->route, array(
						// 		'customer/index', 'customer/update', 'customer/create'
						// 	))),
						// 	),
						// array('label' => 'Backend users', 'url' => array('/backenduser/index'),
						// 	'active' => (Yii::app()->request->getParam('backenduser') || in_array(Yii::app()->controller->route, array(
						// 		'backenduser/index', 'backenduser/update', 'backenduser/create'
						// 	))),
						// ),
					),
				),
				// array(
				// 	'class' => 'bootstrap.widgets.TbMenu',
				// 	'encodeLabel' => true,
				// 	'htmlOptions' => array('class' => 'pull-right'),
				// 	'items' => array(
				// 		array('label' => Yii::app()->user->name, 'icon' => 'user', 'url' => '#', 'visible' => !Yii::app()->user->isGuest, 'items' => array(
				//             array('label' => Yii::t('labels', 'Account'), 'url' => array('/user/view', 'id' => Yii::app()->user->id)),
				// 			'---',
				// 			array('label' => Yii::t('labels', 'Logout'), 'url' => array('/site/logout'))
				// 		)),
				// 		array('label' => Yii::t('labels', 'Login'), 'icon' => 'user', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
				// 	),
				// ),
			),
		)); 
	?>
	<!-- mainmenu -->
	<div id="main-content" class="container">
		<?php if (isset($this->breadcrumbs)): ?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
		<?php endif?>

        <?php
	        $flashMessages = Yii::app()->user->getFlashes();
	        if ($flashMessages) {
	            echo '<div>';
	            echo '<div class="flash-messages">';
	            foreach ($flashMessages as $key => $message) {
	                echo '<div class="alert alert-' . $key . '">' . "
	      				<a class='close' data-dismiss='alert'>×</a>
	   						{$message}
	   					</div>\n";
	            }
	            echo '</div>';
	            echo '</div>';
	        }
        ?>

		<?php echo $content; ?>
		<hr/>
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> <a href="http://www.u-hopper.com" target="_NEW">U-Hopper Srl</a>. All rights reserved.
		</div>
		<!-- footer -->
	</div>
</div>
<!-- page -->
</body>
</html>