<?php $this->beginContent('//layouts/main'); ?>

<div class="row">
	<div class="span3">
		<div class="sidebar-nav">
		  <?php $this->widget('bootstrap.widgets.TbMenu', array(
		  	'type' => 'list',
		  	'encodeLabel' => false,
			'items' => $this->menu,
			'htmlOptions' => array('class' => 'well', 'style' => 'margin-bottom: 20px;'), /** margin-bottom is required for responsive view, when sidebar is displayed above the main area */
			));?>
		</div>
	</div><!--/span3-->
	<div class="span9">
		<?php /*if(isset($this->breadcrumbs)):?>
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		        'links'=>$this->breadcrumbs,
				'homeLink'=>CHtml::link('Dashboard'),
				'htmlOptions'=>array('class'=>'breadcrumb')
		    )); ?><!-- breadcrumbs -->
		<?php endif */?>
	
			<!-- Include content pages -->
			<?php echo $content; ?>
	
	</div><!--span9-->
</div><!--row-->
<?php $this->endContent(); ?>