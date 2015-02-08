<?php $this->beginContent('core.views.layouts.default'); ?>

	<?php $this->widget('Menu', array(
		'id' => 'dashboard-tabs',
		'items' => array(
			array(
				'label' => Yii::t('core.crud', 'My tasks'),
				'url' => array('/core/default/my'),
				'counter' => $this->getDataMy()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'New'),
				'url' => array('/core/default/new'),
				'counter' => $this->getDataNew()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'Scheduled'),
				'url' => array('/core/default/index'),
				'counter' => $this->getDataScheduled()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'Updated'),
				'url' => array('/core/default/updated'),
				'counter' => $this->getDataUpdated()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'Pending'),
				'url' => array('/core/default/pending'),
				'counter' => $this->getDataPending()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'Expired'),
				'url' => array('/core/default/expired'),
				'counter' => $this->getDataExpired()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'Completed'),
				'url' => array('/core/default/completed'),
				'counter' => $this->getDataCompleted()->totalItemCount,
			),
			array(
				'label' => Yii::t('core.crud', 'On hold'),
				'url' => array('/core/default/hold'),
				'counter' => $this->getDataOnHold()->totalItemCount,
			),
		),
		'htmlOptions' => array(
			'class' => 'nav nav-tabs',
		),
	)); ?>

	<?php echo $content; ?>
	
<?php $this->endContent(); ?>
