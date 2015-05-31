<?php
	$this->pageTitle = 'Maintenance';
?>

<div style="text-align:center; vertical-align:middle">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3"></div>
			<div class="well well-white span6">
				<h4><?php echo CHtml::image(Yii::app()->params['frontend-public.url'] . 'images/logo_small.png', '', array('class' => 'brand-logo')); ?> 
							Site is currently under maintenance.</h4>
				<p align="center">We are working to improve the quality of our service.</p>
				<p align="center"><?php echo Yii::app()->name;?> will be again live shortly.</p>
			</div> 
			<div class="span3"></div>
		</div> 
	</div> 
</div>