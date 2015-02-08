<?php

$this->pageHeading = Yii::t('core.crud', 'My Profile');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'My Profile')
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Update'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('updateProfile'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'My Profile'); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,
		'attributes' => array(
			'real_name',
			'username',
			'email',
			'localeName',
		),
	)); ?>
</div>
