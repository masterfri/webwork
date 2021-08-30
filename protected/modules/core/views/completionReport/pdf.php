<?php $this->beginWidget('RenderPdf'); ?>
<style>
	<?php $this->renderPartial('../layouts/include/pdf-style'); ?>
	table td, table th {padding: 0 0 3mm 0;}
	table.bordered td, table.bordered th {padding: 1mm;}
</style>
<page style="font-family:dejavusans;" backtop="10mm" backbottom="10mm">
	<?php $this->renderPartial('../layouts/include/pdf-header'); ?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%;">
				<p>
					<?php echo Yii::t('core.crud', 'Approve'); ?>
					<br /><br />
					<?php echo $model->performer->legalTypeName; ?>
					<br />
					<?php echo $model->performer->legalName; ?>
				</p>
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				<p>
					<?php echo Yii::t('core.crud', 'Approve'); ?>
					<br /><br />
					<?php echo $model->contragent->legalTypeName; ?>
					<br />
					<?php echo $model->contragent->legalName; ?>
				</p>
			</td>
		</tr>
	</table>
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%;">
				<br /><br />
				<div class="hr" />
				<?php echo $model->performer->legal_signer_name; ?>
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				<br /><br />
				<div class="hr" />
				<?php echo $model->contragent->legal_signer_name; ?>
			</td>
		</tr>
	</table>

	<h3>
		<?php echo Yii::t('core.crud', 'Work completion statement'); ?>
		<br /> 
		<?php echo Yii::t('core.crud', '#{number}', array('{number}' => $model->number)); ?>
		<?php echo Yii::t('core.crud', 'from {date}', array('{date}' => $model->date)); ?>
	</h3>
	<div class="hr-thik" />
	
	<p>
		<?php echo Yii::t('core.crud', 'We, the undersigned, representative of customer'); ?>
		<?php echo $model->contragent->legalTypeName; ?>
		<?php echo $model->contragent->legalName; ?>
		<?php echo Yii::t('core.crud', 'on one side, and representative of performer'); ?>
		<?php echo $model->performer->legalTypeName; ?>
		<?php echo $model->performer->legalName; ?>
		<?php echo Yii::t('core.crud', 'on another side agree that performer completed the following work:'); ?>
	</p>

	<table style="width:100%;" class="bordered">
		<tr class="heading">
			<th style="width: 3%;">№</th>
			<th style="width: 37%;"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('name')) ?></th>
			<th style="width: 15%;" align="right"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('qty')) ?></th>
			<th style="width: 15%;"><?php echo Yii::t('core.crud', 'Mesure') ?></th>
			<th style="width: 15%;" align="right"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('price')) ?></th>
			<th style="width: 15%;" align="right"><?php echo Yii::t('core.crud', 'Sum') ?></th>
		</tr>
		<?php 
			$n = 1;
			$sum = 0;
			$format = Yii::app()->format;
			foreach($model->items as $item): ?>
			<tr>
				<td><?php echo $n++; ?></td>
				<td><?php echo CHtml::encode($item->name) ?></td>
				<td align="right"><?php echo $item->qty; ?></td>
				<td><?php echo Yii::t('core.crud', 'Service') ?></td>
				<td align="right"><?php echo $format->formatAbstractMoney($item->price); ?></td>
				<td align="right"><?php echo $format->formatAbstractMoney($item->price * $item->qty); ?></td>
			</tr>
		<?php 
		$sum += $item->price * $item->qty;
		endforeach; ?>
	</table>
	<br />
	<table style="width:100%;">
		<tr>
			<td style="width: 70%;">&nbsp;</td>
			<td style="width: 15%;">&nbsp;<b><?php echo Yii::t('core.crud', 'Total') ?>:</b></td>
			<td style="width: 15%;" align="right"><b><?php echo $format->formatAbstractMoney($sum); ?></b>&nbsp;</td>
		</tr>
	</table>
	<p>
	<?php echo Yii::t('core.crud', 'Total price of completed work is {price}.', array('{price}' => $format->formatMoneySpellout($sum))); ?>
	<br />
	<?php echo Yii::t('core.crud', 'Customer does not have any complaints about quality of the work.'); ?>
	</p>
	<div class="hr-thik" />
	<br />
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%;">
				<b><?php echo Yii::t('core.crud', 'Representative of performer'); ?> <sup>*</sup></b>
				<br /><br /><br /><br />
				<div class="hr" />
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				<b><?php echo Yii::t('core.crud', 'Representative of customer'); ?></b>
				<br /><br /><br /><br />
				<div class="hr" />
			</td>
		</tr>
	</table>
	<br />
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%;">
				<p>
					<sup>*</sup> <?php echo Yii::t('core.crud', 'Responsible for the implementation of the business transaction and the correctness of its design'); ?>
				</p>
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				&nbsp;
			</td>
		</tr>
	</table>
	<br />
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%;">
				<p>
					<b><?php echo $model->date; ?></b><br /><br />
					<?php echo Yii::t('core.crud', 'Performer'); ?>:
					<br />
					<?php echo $model->performer->legalTypeName; ?>
					<br />
					<?php echo $model->performer->legalName; ?>
					<br />
					<?php if ($model->performer->legal_type == User::LEGAL_INDIVIDUAL): ?>
						<?php echo Yii::t('core.crud', 'Tax ID'); ?>: 
						<?php echo $model->performer->legal_number; ?>
						<br />
						<?php echo Yii::t('core.crud', 'Address of registration'); ?>:
						<?php echo $model->performer->legal_address; ?>
					<?php else: ?>
						<?php echo Yii::t('core.crud', 'Employer Identification Number'); ?>: 
						<?php echo $model->performer->legal_number; ?>
						<br />
						<?php echo Yii::t('core.crud', 'Legal address'); ?>:
						<?php echo $model->performer->legal_address; ?>
					<?php endif ?>
				</p>
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				<p>
					<b><?php echo $model->date; ?></b><br /><br />
					<?php echo Yii::t('core.crud', 'Customer'); ?>:
					<br />
					<?php echo $model->contragent->legalTypeName; ?>
					<br />
					<?php echo $model->contragent->legalName; ?>
					<br />
					<?php if ($model->contragent->legal_type == User::LEGAL_INDIVIDUAL): ?>
						<?php echo Yii::t('core.crud', 'Tax ID'); ?>: 
						<?php echo $model->contragent->legal_number; ?>
						<br />
						<?php echo Yii::t('core.crud', 'Address of registration'); ?>:
						<?php echo $model->contragent->legal_address; ?>
					<?php else: ?>
						<?php echo Yii::t('core.crud', 'Employer Identification Number'); ?>: 
						<?php echo $model->contragent->legal_number; ?>
						<br />
						<?php echo Yii::t('core.crud', 'Legal address'); ?>:
						<?php echo $model->contragent->legal_address; ?>
					<?php endif ?>
				</p>
			</td>
		</tr>
	</table>
</page>
<?php $this->endWidget(); ?>
