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
	'template' => CHtml::link('<span class="glyphicon glyphicon-search"></span>', '#', array(
			'class' => 'btn btn-default search-button',
			'data-toggle' => 'search-form',
		)) . 
		'{sorter} {items} {pager}',
)); ?>
