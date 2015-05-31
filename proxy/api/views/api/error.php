<?php

	/**
	 * @author: carlo.caprini@u-hopper.com
	 */
	 
	$this->pageTitle = 'Profilerix - Error';

?>

<div style="text-align:center; vertical-align:middle">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3"></div>
			<div class="well well-white span6">
				<h4><?php echo CHtml::image(Yii::app()->params['frontend-public.url'] . 'images/logo_small.png', '', array('class' => 'brand-logo')) . ' ' . $message; ?></h4>
				<p align="center">(Error <?php echo $code ?>)</p>
				
				<p align="center">Go back to Profilerix <?php echo "<a href=" . Yii::app()->params["frontend.url"] . "site/index>Home</a>"; ?>.</p>
				<?php if ($code == 403) : ?>
					<p align="center"><?php echo "<a href=" . Yii::app()->params["frontend.url"] . "site/login>Login</a>"; ?> or <?php echo "<a href=" . Yii::app()->params["frontend.url"] . "site/register>Register</a>"; ?> if you don't have an account already.</p>
				<?php endif; ?>
			</div> 
			<div class="span3"></div>
		</div> 
	</div> 
</div>