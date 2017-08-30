<?php $this->widget('DetailView', array(
	'data' => $model,
	'attributes' => array(
		'label',
		'module',
		'schemes:array',
		'description:ntext',
	),
)); ?>
<div class="panel-body">
	<h3 class="panel-title"><?php echo Yii::t('appEntity', 'Attributes'); ?></h3>
</div>
<table class="table table-bordered table-condensed attributes-table">
	<thead>
		<tr>
			<th><?php echo Yii::t('appEntity', 'Name'); ?></th>
			<th><?php echo Yii::t('appEntity', 'Type'); ?></th>
			<th><?php echo Yii::t('appEntity', 'Size'); ?></th>
			<th><?php echo Yii::t('appEntity', 'Flags'); ?></th>
			<th width="50%"><?php echo Yii::t('appEntity', 'Details'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($model->getEntityAttributes() as $attribute): ?>
			<tr>
				<td>
					<div><?php echo CHtml::encode($attribute['label']); ?></div>
					<small><?php echo CHtml::encode($attribute['name']); ?></small>
				</td>
				<td>
					<?php echo CHtml::encode($attribute['type']); ?>
				</td>
				<td>
					<?php echo empty($attribute['size']) ? '' : CHtml::encode($attribute['size']); ?>
				</td>
				<td>
					<?php echo isset($attribute['required']) && $attribute['required'] ? '<span class="glyphicon glyphicon-asterisk" title="Required"></span>' : ''; ?>
					<?php echo isset($attribute['readonly']) && $attribute['readonly'] ? '<span class="glyphicon glyphicon-lock" title="Readonly"></span>' : ''; ?>
					<?php echo isset($attribute['sortable']) && $attribute['sortable'] ? '<span class="glyphicon glyphicon-sort-by-attributes" title="Sortable"></span>' : ''; ?>
					<?php echo isset($attribute['searchable']) && $attribute['searchable'] ? '<span class="glyphicon glyphicon-search" title="Searchable"></span>' : ''; ?>
					<?php echo isset($attribute['collection']) && $attribute['collection'] ? '<span class="glyphicon glyphicon-th-large" title="Collection"></span>' : ''; ?>
					<?php echo isset($attribute['tableview']) && $attribute['tableview'] ? '<span class="glyphicon glyphicon-align-justify" title="Table view"></span>' : ''; ?>
					<?php echo isset($attribute['detailview']) && $attribute['detailview'] ? '<span class="glyphicon glyphicon-list-alt" title="Detailed view"></span>' : ''; ?>
				</td>
				<td class="inline-table">
					<table class="table table-stripped table-condensed">
						<tbody>
							<?php if (isset($attribute['unsigned'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Unsigned'); ?></th><td><?php echo $attribute['unsigned'] ? Yii::t('core.crud', 'Yes') : Yii::t('core.crud', 'No'); ?></td></tr>
							<?php endif ?>
							<?php if (isset($attribute['default'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Default value'); ?></th><td><?php echo CHtml::encode($attribute['default']); ?></td></tr>
							<?php endif ?>
							<?php if (!empty($attribute['relation'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Relation'); ?></th><td><?php echo CHtml::encode($attribute['relation']); ?></td></tr>
							<?php endif ?>
							<?php if (!empty($attribute['backref'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Back reference'); ?></th><td><?php echo CHtml::encode($attribute['backref']); ?></td></tr>
							<?php endif ?>
							<?php if (isset($attribute['subordinate'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Subordinate'); ?></th><td><?php echo $attribute['subordinate'] ? Yii::t('core.crud', 'Yes') : Yii::t('core.crud', 'No'); ?></td></tr>
							<?php endif ?>
							<?php if (!empty($attribute['options'])): ?>
								<tr><th width="30%"><?php echo Yii::t('appEntity', 'Options'); ?></th><td><ul class="compact"><li><?php echo implode('</li><li>', explode("\n", CHtml::encode($attribute['options']))); ?></li></ul></td></tr>
							<?php endif ?>
							<?php if (!empty($attribute['description'])): ?>
								<tr><td colspan="2"><?php echo nl2br(CHtml::encode($attribute['description'])); ?></td></tr>
							<?php endif ?>
						</tbody>
					</table>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>