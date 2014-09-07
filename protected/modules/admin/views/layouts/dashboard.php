<?php $this->beginContent('admin.views.layouts.default'); ?>

	<?php $this->widget('Menu', array(
		'items' => array(
			array(
				'label' => Yii::t('admin.crud', 'My tasks'),
				'url' => array('/admin/default/my'),
				'counter' => $this->getDataMy()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'New'),
				'url' => array('/admin/default/new'),
				'counter' => $this->getDataNew()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'Scheduled'),
				'url' => array('/admin/default/index'),
				'counter' => $this->getDataScheduled()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'Updated'),
				'url' => array('/admin/default/updated'),
				'counter' => $this->getDataUpdated()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'Pending'),
				'url' => array('/admin/default/pending'),
				'counter' => $this->getDataPending()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'Expired'),
				'url' => array('/admin/default/expired'),
				'counter' => $this->getDataExpired()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'Completed'),
				'url' => array('/admin/default/completed'),
				'counter' => $this->getDataCompleted()->totalItemCount,
			),
			array(
				'label' => Yii::t('admin.crud', 'On hold'),
				'url' => array('/admin/default/hold'),
				'counter' => $this->getDataOnHold()->totalItemCount,
			),
		),
		'htmlOptions' => array(
			'class' => 'nav nav-tabs',
		),
	)); ?>

	<?php echo $content; ?>
	
<?php $this->endContent(); ?>
