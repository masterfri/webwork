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

<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Working Hours'); ?></h3>
			</div>
			<table class="table table-striped table-bordered table-condensed detailed-sum">
				<tr>
					<th><?php echo Yii::t('workingHours', 'Monday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 1)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Thuesday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 2)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Wednesday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 3)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Thursday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 4)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Friday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 5)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Saturday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 6)) > 0 ? $h : '-' ?></td>
				</tr>
				<tr>
					<th><?php echo Yii::t('workingHours', 'Sunday'); ?></th>
					<td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 7)) > 0 ? $h : '-' ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Holidays'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6">
						<table class="table table-bordered table-condensed detailed-sum calendar">
							<tr class="calendar-month">
								<th colspan="7"><?php echo CHtml::encode(Yii::t('monthNames', $month1)); ?></th>
							</tr>
							<tr class="calendar-head">
								<th><?php echo Yii::t('workingHours', 'Mon'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Tue'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Wed'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Thu'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Fri'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Sat'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Sun'); ?></th>
							</tr>
							<?php while ($month1i <= $month1days): ?>
								<tr class="calendar-days">
									<?php for ($i = 0; $i < 7; $i++): ?>
										<?php if ($month1i >= 1 && $month1i <= $month1days): ?>
											<td class="<?php echo (WorkingHours::checkUserHours($model->id, $i + 1) > 0 && !Holiday::checkDate($month1i, $monthnum, null, $model->id)) ? 'working' : 'non-working'; ?>">
												<?php echo date('j', mktime(0,0,0, $monthnum, $month1i)); ?>
											</td>
										<?php else: ?>
											<td>&nbsp;</td>
										<?php endif; ?>
									<?php $month1i++; endfor; ?>
								</tr>
							<?php endwhile; ?>
						</table>
					</div>
					<div class="col-sm-6">
						<table class="table table-bordered table-condensed detailed-sum calendar">
							<tr class="calendar-month">
								<th colspan="7"><?php echo CHtml::encode(Yii::t('monthNames', $month2)); ?></th>
							</tr>
							<tr class="calendar-days">
								<th><?php echo Yii::t('workingHours', 'Mon'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Tue'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Wed'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Thu'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Fri'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Sat'); ?></th>
								<th><?php echo Yii::t('workingHours', 'Sun'); ?></th>
							</tr>
							<?php while ($month2i <= $month2days): ?>
								<tr class="calendar-days">
									<?php for ($i = 0; $i < 7; $i++): ?>
										<?php if ($month2i >= 1 && $month2i <= $month2days): ?>
											<td class="<?php echo (WorkingHours::checkUserHours($model->id, $i + 1) > 0 && !Holiday::checkDate($month2i, $monthnum + 1, null, $model->id)) ? 'working' : 'non-working'; ?>">
												<?php echo date('j', mktime(0,0,0, $monthnum + 1, $month2i)); ?>
											</td>
										<?php else: ?>
											<td>&nbsp;</td>
										<?php endif; ?>
									<?php $month2i++; endfor; ?>
								</tr>
							<?php endwhile; ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
