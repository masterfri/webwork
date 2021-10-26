<?php $this->beginWidget('RenderPdf');
$format = Yii::app()->format; ?>
<style>
	<?php $this->renderPartial('../layouts/include/pdf-style'); ?>
	table td, table th {padding: 0 0 3mm 0;}
	table.bordered td, table.bordered th {padding: 1mm;}
</style>
<page style="font-family:dejavusans;" backtop="10mm" backbottom="10mm">
	<?php $this->renderPartial('../layouts/include/pdf-header'); ?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 45%; vertical-align: top;">
				<p>
					<?php echo mb_strtoupper(Yii::t('core.crud', 'Approve')); ?>
					<br /><br />
					<?php echo $model->performer->legalTypeName; ?>
					<br />
					<?php echo CHtml::encode($model->performer->legalName); ?>
					<br />
					<?php if ($model->performer->legal_type == User::LEGAL_INDIVIDUAL): ?>
						<?php echo Yii::t('core.crud', 'Tax ID'); ?>: 
						<?php echo CHtml::encode($model->performer->legal_number); ?>
						<br />
						<?php echo Yii::t('core.crud', 'Address of registration'); ?>:
						<?php echo nl2br(CHtml::encode($model->performer->legal_address)); ?>
					<?php else: ?>
						<?php echo Yii::t('core.crud', 'Employer Identification Number'); ?>: 
						<?php echo CHtml::encode($model->performer->legal_number); ?>
						<br />
						<?php echo Yii::t('core.crud', 'Legal address'); ?>:
						<?php echo nl2br(CHtml::encode($model->performer->legal_address)); ?>
						<br /><br />
						<?php echo Yii::t('core.crud', 'Manager'); ?>
						<br />
						<?php echo CHtml::encode($model->performer->legal_signer_name); ?>
					<?php endif ?>
				</p>
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%; vertical-align: top;">
				<p>
					<?php echo mb_strtoupper(Yii::t('core.crud', 'Approve')); ?>
					<br /><br />
					<?php echo $model->contragent->legalTypeName; ?>
					<br />
					<?php echo CHtml::encode($model->contragent->legalName); ?>
					<br />
					<?php if ($model->contragent->legal_type == User::LEGAL_INDIVIDUAL): ?>
						<?php echo Yii::t('core.crud', 'Tax ID'); ?>: 
						<?php echo CHtml::encode($model->contragent->legal_number); ?>
						<br />
						<?php echo Yii::t('core.crud', 'Address of registration'); ?>:
						<?php echo nl2br(CHtml::encode($model->contragent->legal_address)); ?>
					<?php else: ?>
						<?php echo Yii::t('core.crud', 'Employer Identification Number'); ?>: 
						<?php echo CHtml::encode($model->contragent->legal_number); ?>
						<br />
						<?php echo Yii::t('core.crud', 'Legal address'); ?>:
						<?php echo nl2br(CHtml::encode($model->contragent->legal_address)); ?>
						<br /><br />
						<?php echo Yii::t('core.crud', 'Manager'); ?>
						<br />
						<?php echo CHtml::encode($model->contragent->legal_signer_name); ?>
					<?php endif ?>
				</p>
			</td>
		</tr>
	</table>
	
	<h3>
		<?php echo Yii::t('core.crud', 'Work completion statement'); ?>
		<br /> 
		<?php echo Yii::t('core.crud', '#{number}', array('{number}' => $model->number)); ?>
		<?php echo Yii::t('core.crud', 'from {date}', array('{date}' => mb_strtolower($format->formatDateFull($model->date)))); ?>
	</h3>
	<div class="hr-thik" />
	
	<p>
		<?php echo Yii::t('core.crud', 'We, the undersigned, representative of customer {customerType} {customerName} on one side, and representative of performer {performerType} {performerName} on another side made this statement about the fact, that performer have completed the work according to contract #{contractNumber} from {contractDate}.', array(
			'{customerType}' => $model->contragent->legalTypeName,
			'{customerName}' => CHtml::encode($model->contragent->legalName),
			'{performerType}' => $model->performer->legalTypeName, 
			'{performerName}' => CHtml::encode($model->performer->legalName),
			'{contractNumber}' => CHtml::encode($model->contract_number),
			'{contractDate}' => mb_strtolower($format->formatDateFull($model->contract_date)),
		)); ?>
	</p>

	<table style="width:100%;" class="bordered">
		<tr class="heading">
			<th style="width: 5%;">№</th>
			<th style="width: 47%;"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('name')) ?></th>
			<th style="width: 12%;" align="right"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('qty')) ?></th>
			<th style="width: 12%;"><?php echo Yii::t('core.crud', 'Mesure') ?></th>
			<th style="width: 12%;" align="right"><?php echo CHtml::encode(CompletedJob::model()->getAttributeLabel('price')) ?></th>
			<th style="width: 12%;" align="right"><?php echo Yii::t('core.crud', 'Sum') ?></th>
		</tr>
		<?php 
			$n = 1;
			$sum = 0;
			foreach($model->items as $item): ?>
			<tr style="vertical-align: top">
				<td><?php echo $n++; ?></td>
				<td><?php echo wordwrap(CHtml::encode($item->name), 60, '<br />') ?></td>
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
				<?php echo Yii::t('core.crud', 'Handed over the work'); ?>
				<br />
				<?php echo Yii::t('core.crud', 'Representative of performer'); ?> <sup>*</sup>
				<br /><br />
				<?php echo CHtml::encode($model->performer->legal_signer_name); ?>
				<br /><br /><br /><br />
				<div class="hr" />
			</td>
			<td style="width: 10%;">&nbsp;</td>
			<td style="width: 45%;">
				<?php echo Yii::t('core.crud', 'Accepted the work'); ?>
				<br />
				<?php echo Yii::t('core.crud', 'Representative of customer'); ?>
				<br /><br />
				<?php echo CHtml::encode($model->contragent->legal_signer_name); ?>
				<br /><br /><br /><br />
				<div class="hr" />
			</td>
		</tr>
	</table>
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
</page>
<?php $this->endWidget(); ?>
