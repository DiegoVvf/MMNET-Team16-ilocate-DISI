<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'localization-system-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
        $list = CHtml::listData(LocalizationSystemType::model()->findAll(array()), 'id', 'type');
        echo $form->dropDownList($model, 'id_type', $list);
    ?>

    <div class="row-fluid">
		<?php
		    echo $form->select2Row($model, 'id_deployment', array(
		        'id' => 'select_deployment',
		        'labelOptions' => array(
		            'label' => Yii::t('labels', 'Deployment'),
		        ),
		        'data' => CHtml::listData(Deployment::model()->findAll(), 'id', 'name'),
		        'asDropDownList' => true,
		        // 'class' => 'span10',
		        'options' => array(
		            'allowClear' => true,
		            'placeholder' => Yii::t('labels', 'Select a deployment ...'),
		            'width' => '40%',
		        ),
		    ));
		?>
    </div>

    <div class="row-fluid">
    <?php
        echo $form->textFieldRow($model, 'id_environment', array(
            'class' => 'span5',
            // 'width' => '40%',
            'id' => 'select_environment',
            'labelOptions' => array(
                'label' => Yii::t('labels', 'Environment'),
            ),
        ));
    ?>
    </div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    var environmentsOpts = {
        data: [],
        placeholder: "<?php echo Yii::t('labels', 'Select environment ...'); ?>",
        allowClear: true
    };

    function emptyEnvironments() {
        $("#select_environment").val('');
        $("#select_environment").select2($.extend({}, environmentsOpts)).trigger("change");
    }

    function retrieveAvailableEnvironments(id_deployment, callback) {
        if (id_deployment !== '') {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('deployment/availableEnvironments'); ?>?id=" + id_deployment,
                dataType: 'json'
            })
                    .done(function (data) {
                        var items = [];
                        $.each(data, function (index, item) {
                            items.push({
                                id: item.id,
                                text: item.name
                            });
                        });
                        callback && callback(items);
                    });
        }
    }

    $(document).ready(function () {
        emptyEnvironments();

        $("#select_environment")
            .on("select2-opening", function () {
                if ($("#select_deployment").select2("val") === '') {
                    bootbox.alert("<?php echo Yii::t('labels', 'Please, select deployment first'); ?>");
                    return false;
                }
                else {
                    return true;
                }
            });

        $("#select_deployment")
            .on("change", function (e) {
                var id_deployment = e.val;
                if (id_deployment !== '') {
                	console.log('using deployment : ' + id_deployment);
                    retrieveAvailableEnvironments(id_deployment, function (environments) {
                        $("#select_environment").select2($.extend({}, environmentsOpts, {data: environments})).trigger("change");
                    });
                }
                else {
                    emptyEnvironments();
                }
            });

		<?php //if (!empty($model->id_deployment)): ?>

       //      retrieveAvailableEnvironments(<?php echo $model->id_deployment; ?>, function (environments) {
       //          $("#select_environment").select2($.extend({}, environmentsOpts, {data: environments})).trigger("change");

	    		// <?php if (!empty($model->id_environment)): ?>
	      //           $("#select_environment").select2("val", "<?php echo $model->id_environment; ?>");
	    		// <?php endif; ?>
       //      });

		<?php //endif; ?>

    });


</script>