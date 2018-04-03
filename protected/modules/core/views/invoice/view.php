<?php

$this->pageHeading = Yii::t('core.crud', 'Invoice Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Invoices') => Yii::app()->user->checkAccess('view_invoice') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Invoice'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Invoices'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>', 
		'linkOptions' => array(
			'class' => 'btn btn-default dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'itemOptions' => array(
			'class' => 'dropdown',
		),
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::t('core.crud', 'Export to PDF'), 
				'url' => array('pdf', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_invoice', array('invoice' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-usd"></i> ' . Yii::t('core.crud', 'Make a Payment'), 
				'url' => array('payment/create', 'invoice' => $model->id),
				'visible' => Yii::app()->user->checkAccess('create_payment'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Invoice'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_invoice'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Invoice'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this invoice?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_invoice'),
			),
		),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			array(
				'name' => 'id',
				'value' => $model->getNumber(),
			),
			'from',
			'to',
			array(
				'name' => 'project',
				'type' => 'raw',
				'value' => $model->project && Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ?
					CHtml::link(CHtml::encode($model->project), array('project/view', 'id' => $model->project_id)) :
					$model->project,
			),
			'comments',
			'total_hours:hours',
			'amount:money',
			'payd:money',
			'draft:boolean',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('tag', 'Created by'); ?>
		<?php echo CHtml::encode(CHtml::value($model, 'created_by', Yii::t('core.crud', 'System'))); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('invoice', 'Items') ?></h3>
	</div>
	<table class="table table-striped table-condensed grid-view">
		<thead>
			<tr>
				<th width="30">#</th>
				<th><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('name')) ?></th>
				<th><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('hours')) ?></th>
				<th><?php echo CHtml::encode(InvoiceItem::model()->getAttributeLabel('value')) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$groups = $model->getItemsGroups();
			$format = Yii::app()->format;
			$n = 1;
			foreach($model->getItemsGroups() as $id => $group): ?>
				<?php if ($id != 0 || count($groups) > 0): ?>
					<tr class="row-group-title">
						<th colspan="4">
							<?php echo $id == 0 ? Yii::t('invoice', 'Other') : CHtml::encode($group['name']); ?>
						</th>
					</tr>
				<?php endif; ?>
				<?php foreach($group['items'] as $item): ?>
					<tr>
						<td><?php echo $n++; ?></td>
						<td><?php echo CHtml::encode($item->name) ?></td>
						<td><?php echo $format->formatHours($item->hours); ?></td>
						<td>
							<?php if ($item->bonus != 0): ?>
								<span class="text-info" title="<?php echo Yii::t('core.crud', 'Including bonus of {bonus}', array('{bonus}' => $format->formatMoney($item->bonus))) ?>"><?php echo $format->formatMoney($item->value); ?> *</span>
							<?php else: ?>
								<?php echo $format->formatMoney($item->value); ?>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr class="row-total">
					<td>&nbsp;</td>
					<td><?php echo Yii::t('core.crud', 'Subtotal') ?></td>
					<td><?php echo $format->formatHours($group['total_hours']); ?></td>
					<td><?php echo $format->formatMoney($group['total_amount']); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

