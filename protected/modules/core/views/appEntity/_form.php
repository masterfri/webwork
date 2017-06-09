<?php

$cf = $model->application->getCF();

?>

<div class="form-content">
		
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'appEntity-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#appEntity-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
	
	<?php echo $form->hiddenField($model, 'json_source'); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'name', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'name', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'module', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'module', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'module', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'label', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'label', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'label', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'description', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textArea($model, 'description', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'schemes', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->tagField($model, 'schemes', array_combine($cf->getCompileSchemes(), $cf->getCompileSchemes()), array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'schemes', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-8">
			<h3><?php echo Yii::t('appEntity', 'Attributes'); ?></h3>
		</div>
		<div class="col-sm-4">
			<div class="pull-right">
				<?php $this->widget('zii.widgets.CMenu', array(
					'items' => array(
						array(
							'label' => '<i class="glyphicon glyphicon-plus"></i>', 
							'linkOptions' => array(
								'title' => Yii::t('core.crud', 'Add'), 
								'class' => 'btn btn-default',
								'id' => 'add-attribute',
							), 
							'url' => '#',
						),
						array(
							'label' => '<label class="file-btn-wrapper"><i class="glyphicon glyphicon-import"></i><input type="file" /></label>', 
							'linkOptions' => array(
								'title' => Yii::t('core.crud', 'Import attributes from CSV'), 
								'class' => 'btn btn-default',
								'id' => 'import-attributes',
							), 
							'url' => '#',
						),
					),
					'encodeLabel' => false,
					'htmlOptions' => array(
						'class' => 'nav nav-pills context-menu',
					),
				)); ?>
			</div>
		</div>
	</div>
	<div id="attributes-list"></div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::button(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary', 'id' => 'submit-button')); ?>
		</div>
	</div>
	
	<?php $this->endWidget(); ?>
</div>

<?php

$types = CJSON::encode(array(
	'std' => $cf->getStandardTypes(),
	'custom' => $cf->getCustomTypes(),
	'rel' => array_values($model->application->getEntitiesList()),
));
if ($model->getIsNewRecord()) {
	$refs = 'false';
} else {
	$refs = $model->application->getEntityReferences($model->name);
	$refs = CJSON::encode(empty($refs) ? false : $refs[$model->name]);
}
$src = empty($model->json_source) ? 'false' : $model->json_source;

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile('/rc/js/appdesign.js');
$cs->registerScriptFile('/rc/js/papaparse.min.js');
$cs->registerScript('appdesign', 
<<<EOS
var list = $('#attributes-list');
var jsrc = $src;
var types = $types;
var refs = $refs;

$('#add-attribute').on('click', function() {
	var attr = new AppEntityAttribute(null, types, refs);
	list.append(attr.getView());
	list.sortable('refresh');
	attr.getView().find('.attrname').get(0).focus();
	attr.getView().data('model', attr);
	return false;
});

$('#import-attributes').on('change', 'input[type=file]', function() {
	var input = $(this);
	if (this.files.length > 0) {
		var file = this.files[0];
		if (file.type == 'text/plain' || file.type == 'text/csv') {
			Papa.parse(file, {
				'complete': function(result) {
					if (result.data.length > 1) {
						var i, map = result.data[0].map(function(v) {return v.toLowerCase();}), pool = {};
						for (i = 1; i < result.data.length; i++) {
							var attr = new AppEntityAttribute(convertCsvAttribute(result.data[i], map, pool, types), types, refs);
							list.append(attr.getView());
							attr.getView().data('model', attr);
						}
						list.sortable();
					}
				},
				'error': function() {
					alert('Invalid CSV file!');
				},
			});
		} else {
			alert('Invalid CSV file!');
		}
		input.replaceWith(input.clone());
	}
});

$('#submit-button').on('click', function() {
	var src = {
		'attributes': []
	};
	var valid = true;
	list.children().each(function() {
		var attr = $(this).data('model');
		if (attr) {
			if (attr.isValid()) {
				src.attributes.push(attr.getData());
			} else {
				valid = false;
			}
		}
	});
	if (valid) {
		$('#AppEntity_json_source').val(JSON.stringify(src));
		$('#appEntity-form').submit();
	}
	return false;
});

if (jsrc) {
	jsrc.attributes.forEach(function(d) {
		var attr = new AppEntityAttribute(d, types, refs);
		list.append(attr.getView());
		attr.getView().data('model', attr);
	});
}

list.sortable();

function convertCsvAttribute(row, map, pool, types) {
	var result = {}, tmp = {}, i;
	for (i = 0; i < map.length; i++) {
		tmp[map[i]] = row[i];
	}
	result.label = tmp.label;
	result.name = tmp.name;
	if (!result.name) {
		result.name = result.label.toLowerCase()
			.replace(/[^a-zA-Z0-9_]/g, '_')
			.replace(/_+/g, '_')
			.replace(/^_+/, '')
			.replace(/_+$/, '')
			.substr(0, 60);
	}
	if (pool[result.name] === undefined) {
		pool[result.name] = 1;
	} else {
		pool[result.name]++;
		result.name += '_' + pool[result.name];
	}
	if (types.std && types.std.indexOf(tmp.type) != -1) {
		result.type = tmp.type;
		if (result.type == 'char') {
			result.size = tmp.size ? tmp.size : '255';
		} else if (result.type == 'decimal') {
			result.size = tmp.size ? tmp.size : '10,3';
		}
	} else if (types.custom && types.custom.indexOf(tmp.type) != -1 || types.rel && types.rel.indexOf(tmp.type) != -1) {
		result.type = tmp.type;
	} else if (tmp.type && tmp.type.indexOf('\\n') != -1) {
		result.type = 'enum';
		result.options = tmp.type;
	} else { 
		result.type = 'char';
		result.size = '255';
	}
	result.required = ['1', 'y', 'Y'].indexOf(tmp.required) != -1;
	return result;
}
EOS
);
