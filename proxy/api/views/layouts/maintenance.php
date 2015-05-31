<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>

	<link rel="icon" href="<?php echo Yii::app()->params['frontend-public.url']; ?>images/logo_icon.ico" type="image/x-icon"/>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css"
          media="screen, projection"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
	      media="screen, projection"/>
	<![endif]-->

	
</head>

<body>
<!-- mainmenu -->
<div class="container" id="page">
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'null', // null or 'inverse'
	'brand' => CHtml::image(Yii::app()->params['frontend-public.url'] . 'images/logo_small.png', '', array('class' => 'brand-logo')) . ' ' . CHtml::encode(Yii::app()->name),
    'brandUrl' => Yii::app()->request->getBaseUrl(true),
	'fluid' => false,
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				// array('label' => 'Login', 'url' => array('site/login'), 'visible' => Yii::app()->user->isGuest),
				// array('label' => 'Back Office', 'url'=>'http://admin.profilerix.com', 'visible'=>Yii::app()->user->isAdmin),
				// array('label' => 'Dashboard', 'url'=>array('customer/index'), 'visible'=>Yii::app()->user->isRegistered),
				//array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
				
				//array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
				// array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
			),
		),
		)
	)); 
?> <!-- mainmenu -->
	
	<div class="container" style="margin-top:80px">
		<?php if (isset($this->breadcrumbs)): ?>
			<?php 
				$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links' => $this->breadcrumbs,
				));
			?> <!-- breadcrumbs -->
		<?php endif?>

		<?php echo $content; ?>
		
	</div> <!-- container -->
	
</div> <!-- page -->

</body>
</html>