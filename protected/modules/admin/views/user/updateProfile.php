<?php

$this->pageHeading = Yii::t('admin.crud', 'Profile Updating');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'My Profile') => array('profile'), 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'My Profile'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('profile'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_profile_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>
