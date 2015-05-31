<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'environment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->labelEx($model,'id_deployment'); ?>
	<?php
        $list = CHtml::listData(Deployment::model()->findAll(array()), 'id', 'name');
        echo $form->dropDownList($model, 'id_deployment', $list);
    ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
