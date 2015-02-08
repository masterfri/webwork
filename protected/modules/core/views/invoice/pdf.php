<?php $this->beginWidget('RenderPdf'); ?>
<style>
	<?php $this->renderPartial('../layouts/include/pdf-style'); ?>
	table td, table th {padding: 0 0 3mm 0;}
	table.bordered td, table.bordered th {padding: 1mm;}
</style>
<page style="font-family:dejavusans;" backtop="10mm" backbottom="10mm">
	<?php $this->renderPartial('../layouts/include/pdf-header'); ?>
	<h3><?php echo Yii::t('invoice', 'Invoice'); ?> <?php echo $model->getNumber(); ?></h3>
	<table style="width: 100%;">
		<tr>
			<td style="width: 50%;">
				<?php echo Yii::t('invoice', 'From'); ?>:
				<?php echo CHtml::encode($model->from); ?>
			</td>
			<td style="width: 50%;">
				<?php echo Yii::t('invoice', 'To'); ?>:
				<?php echo CHtml::encode($model->to); ?>
			</td>
		</tr>
	</table>
	<?php if('' != $model->comments): ?>
		<p><?php echo nl2br(CHtml::encode($model->comments)); ?></p>
	<?php endif; ?>
	<table style="width:100%;" class="bordered">
		<tr class="heading">
			<th style="width: 3%;">#</th>
			<th style="width: 67%;"><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('name')) ?></th>
			<th style="width: 15%;" align="right"><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('hours')) ?></th>
			<th style="width: 15%;" align="right"><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('value')) ?></th>
		</tr>
		<?php 
		$groups = $model->getItemsGroups();
		$format = Yii::app()->format;
		$n = 1;
		foreach($model->getItemsGroups() as $id => $group): ?>
			<?php if ($id != 0 || count($groups) > 0): ?>
				<tr class="group1">
					<th colspan="4">
						<?php echo $id == 0 ? Yii::t('invoice', 'Other') : CHtml::encode($group['name']); ?>
					</th>
				</tr>
			<?php endif; ?>
			<?php foreach($group['items'] as $item): ?>
				<tr>
					<td><?php echo $n++; ?></td>
					<td><?php echo CHtml::encode($item->name) ?></td>
					<td align="right"><?php echo $format->formatHours($item->hours); ?></td>
					<td align="right"><?php echo $format->formatMoney($item->value); ?></td>
				</tr>
			<?php endforeach; ?>
			<tr class="total1">
				<td>&nbsp;</td>
				<td><?php echo Yii::t('core.crud', 'Subtotal') ?></td>
				<td align="right"><?php echo $format->formatHours($group['total_hours']); ?></td>
				<td align="right"><?php echo $format->formatMoney($group['total_amount']); ?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="total2">
			<td>&nbsp;</td>
			<td><?php echo Yii::t('core.crud', 'Total') ?></td>
			<td align="right"><?php echo $format->formatHours($model->total_hours); ?></td>
			<td align="right"><?php echo $format->formatMoney($model->amount); ?></td>
		</tr>
	</table>
</page>
<?php $this->endWidget(); ?>
