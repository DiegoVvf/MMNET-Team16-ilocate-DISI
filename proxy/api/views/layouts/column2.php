<?php $this->beginContent('//layouts/main'); ?>

<div class="row">
	    <div class="span3">
            <div class="sidebar-nav sidebar-nav-fixed">
             	<?php $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => 'list',
                    'encodeLabel' => false,
                    'items' => array(
                    	array('label'=>'Panel Options', 'itemOptions'=>array('class'=>'nav-header'), 'visible' => Yii::app()->user->isRegistered),
                    	// '',
                 	    array('label'=>Yii::t('labels', 'Control Panel'), 'url'=>array('customer/index'), 'icon' => 'th-large'),
                 		'',
					    array('label'=>Yii::t('labels', 'Account'), 'itemOptions'=>array('class'=>'nav-header'), 'visible' => Yii::app()->user->isRegistered),
					    // '',
					    // array('label'=>'View Account', 'url'=>'#', 'itemOptions'=>array('class'=>'active')),
					    array('label'=>Yii::t('labels', 'Account'), 'url'=>array('customer/view'), 'icon' => 'icon-user'),
					    '',
					    //array('label'=>'Modify Account', 'url'=>array('customer/update')),
					    //array('label'=>'Delete Account', 'url'=>'#'),
					    array('label'=>Yii::t('labels', 'Users'), 'itemOptions'=>array('class'=>'nav-header')),
					    // '',
					    array('label'=>Yii::t('labels', 'Users'), 'url'=>array('user/index'), 'icon' => 'icon-search'),
					    '',
					    array('label'=>Yii::t('labels', 'Analytics'), 'itemOptions'=>array('class'=>'nav-header')),
					    // '',
					    // array('label'=>'User base - Statistics', 'url'=>array('statistic/index'), 'icon' => 'icon-signal'),
					    array('label'=>Yii::t('labels', 'Aggregated Profile'), 'url'=>array('statistic/aggregatedprofile'), 'icon' => 'icon-signal'),
					    array('label'=>Yii::t('labels', 'Aggregated Demographics'), 'url'=>array('statistic/aggregateddemographics'), 'icon' => 'icon-signal'),
					    array('label'=>Yii::t('labels', 'Geo Localization'), 'url'=>array('statistic/localization'), 'icon' => 'icon-signal'),
					    // array('label'=>'Advanced Analytics', 'url'=>'#', 'icon' => 'icon-signal'),
					    // '',
					    // array('label'=>Yii::t('labels', 'Help'), 'url'=>'#', 'icon' => 'icon-edit'),
					),
                    'htmlOptions' => array('class' => 'well', 'style' => 'margin-bottom: 20px;'), /** margin-bottom is required for responsive view, when sidebar is displayed above the main area */
				));?>
            </div> <!-- sidebar -->
	    </div> <!-- span3 -->
	    <div class="span9">
			<?php echo $content; ?>
	    </div><!--span9-->
</div><!--row-->

<?php $this->endContent(); ?>
