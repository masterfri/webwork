<div class="panel <?php echo $response->getIsSuccess() ? 'panel-success' : 'panel-danger'; ?>">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($response->getMessage()); ?></h3>
	</div>
	<?php if ('' != ($raw = $response->getRaw())): ?>
		<div class="panel-body">
			<b><?php echo Yii::t('core.crud', 'Command output'); ?>:</b>
			<pre><?php echo CHtml::encode($response->getRaw()); ?></pre>
		</div>
	<?php endif; ?>
</div>
