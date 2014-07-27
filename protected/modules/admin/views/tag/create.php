<?php

$this->pageHeading = Yii::t('admin.crud', 'New Tag');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Tag') => Yii::app()->user->checkAccess('view_tag') ? array('index') : false, 
	Yii::t('admin.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Tag'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_tag'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>
