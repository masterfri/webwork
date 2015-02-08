<div class="search-form task-list-search" style="display: none;">
	<div class="panel-body">
		<?php $this->renderPartial('_search',array(
			'model' => $model,
		)); ?>
	</div>
</div>

<?php $this->widget('TaskListView', array(
	'id' => 'task-grid',
	'dataProvider' => $provider,
	'group_by_date' => $group_date,
	'template' => 
	CHtml::link('<span class="glyphicon glyphicon-search"></span>', '#', array(
		'class' => 'btn btn-default search-button',
		'data-toggle' => 'search-form',
		'title' => Yii::t('core.crud', 'Search'),
	)) . 
	($mark_read_button ? CHtml::link('<span class="glyphicon glyphicon-ok"></span>', array('default/markAllSeen'), array(
		'class' => 'btn btn-default mark-read-button',
		'title' => Yii::t('core.crud', 'Mark all as seen'),
		'data-raise' => 'ajax-request',
		'data-confirmation' => Yii::t('core.crud', 'Are you sure you want to mark all tasks as seen?'),
	)) : '') .
	'{sorter} {items} {pager}',
)); ?>
