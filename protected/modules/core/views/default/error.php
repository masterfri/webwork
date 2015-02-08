<div class="row">
	<div class="hidden-xs" style="padding-top: 100px;"></div>
	<div style="padding-top: 20px;"></div>
	<div class="col-lg-4 col-lg-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $code; ?></div>
			<div class="panel-body">
				<p>
					<?php echo CHtml::encode($message); ?>
				</p>
				<ul class="nav nav-pills">
					<li><?php echo CHtml::link('<span class="glyphicon glyphicon-home"></span> ' . Yii::t('core.crud', 'Dashboard'), array('/core')); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
