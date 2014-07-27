<?php $this->beginContent('admin.views.layouts.default'); ?>

	<?php $this->widget('Menu', array(
		'items' => array(
			array(
				'label' => Yii::t('admin.crud', 'My tasks'),
				'url' => array('/admin/default/my'),
			),
			array(
				'label' => Yii::t('admin.crud', 'New'),
				'url' => array('/admin/default/new'),
			),
			array(
				'label' => Yii::t('admin.crud', 'Scheduled'),
				'url' => array('/admin/default/index'),
			),
			array(
				'label' => Yii::t('admin.crud', 'Updated'),
				'url' => array('/admin/default/updated'),
			),
			array(
				'label' => Yii::t('admin.crud', 'Pending'),
				'url' => array('/admin/default/pending'),
			),
			array(
				'label' => Yii::t('admin.crud', 'Expired'),
				'url' => array('/admin/default/expired'),
			),
			array(
				'label' => Yii::t('admin.crud', 'Completed'),
				'url' => array('/admin/default/completed'),
			),
			array(
				'label' => Yii::t('admin.crud', 'On hold'),
				'url' => array('/admin/default/hold'),
			),
		),
		'htmlOptions' => array(
			'class' => 'nav nav-tabs',
		),
	)); ?>

	<?php echo $content; ?>
	
<?php $this->endContent(); ?>
