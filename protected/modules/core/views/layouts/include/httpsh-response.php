<div id="httpsh-output-panel" class="panel-group httpsh-output-panel">
	<div class="panel <?php echo $response->getIsSuccess() ? 'panel-success' : 'panel-danger'; ?>">
		<?php if ('' != ($raw = $response->getRaw())): ?>
			<div class="panel-heading">
				<a href="#httpsh-output" class="pull-right" data-toggle="collapse" data-parent="#httpsh-output-panel" title="<?php echo Yii::t('core.crud', 'See programm output'); ?>"><span class="glyphicon glyphicon-eye-open"></span></a>
				<h3 class="panel-title"><?php echo CHtml::encode($response->getMessage()); ?></h3>
			</div>
			<div id="httpsh-output" class="panel-collapse collapse">
				<div class="panel-body">
					<pre><?php echo CHtml::encode($raw); ?></pre>
				</div>
			</div>
		<?php else: ?>
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo CHtml::encode($response->getMessage()); ?></h3>
			</div>
		<?php endif; ?>
	</div>
</div>
