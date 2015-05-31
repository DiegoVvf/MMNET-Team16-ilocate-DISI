<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>
	
	<link rel="icon" href="<?php echo Yii::app()->params['frontend.url']; ?>images/logo_icon.ico" type="image/x-icon"/>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css"
          media="screen, projection"/>
</head>

<body>
	
	<div class="nav-bar link-gray">
		<?php $this->widget('bootstrap.widgets.TbNavbar',
			array(
				'type' => 'inverse', 
				'brand' => CHtml::encode(Yii::app()->name),
				'brandUrl' => array('api/index'),
				'htmlOptions' => array(),
				'collapse' => false,
				'items' => array(
					array(
						'class' => 'bootstrap.widgets.TbMenu',
						'items' => array(
							// array('label' => Yii::t('app', 'Home'), 'url' => array('site/index')),
						),
					),
				)
			));
		?> 
		<div class="container" style="margin-top:80px">
			<div style="position: relative;">
				<?php echo $content; ?>
			</div>
		</div> 
		
	</div> 

</body>
</html>