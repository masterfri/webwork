<?php

class TaskListView extends ListView
{
	public $htmlOptions = array('class' => 'task-list');
	public $template = '{sorter} {items} {pager}';
	public $itemView = '/task/_task';
    public $sortableAttributes = array('name','priority','date_sheduled','due_date','time_updated');
	public $group_by_date = false;
	protected $group_date;
	
	public function renderItems()
	{
		echo CHtml::openTag($this->itemsTagName, array('class' => $this->itemsCssClass)) . "\n";
		
		$data = $this->dataProvider->getData();
		if (($n = count($data)) > 0) {
			$owner = $this->getOwner();
			$viewFile = $owner->getViewFile($this->itemView);
			$j = 0;
			$format = Yii::app()->format;
			foreach($data as $i=>$item) {
				$data = $this->viewData;
				$data['index'] = $i;
				$data['data'] = $item;
				$data['widget'] = $this;
				
				if ($this->group_by_date) {
					if ($item->date_sheduled != $this->group_date) {
						if (date('Y-m-d', strtotime('-1 day')) == $item->date_sheduled) {
							$formatted_date = Yii::t('core.crud', 'Yesterday');
						} elseif (date('Y-m-d') == $item->date_sheduled) {
							$formatted_date = Yii::t('core.crud', 'Today');
						} elseif (date('Y-m-d', strtotime('+1 day')) == $item->date_sheduled) {
							$formatted_date = Yii::t('core.crud', 'Tomorrow');
						} else {
							$formatted_date = $format->formatDate($item->date_sheduled);
						}
						echo CHtml::tag('div', array('class' => 'group-date'), $formatted_date);
						$this->group_date = $item->date_sheduled;
					}
				}
				
				$owner->renderFile($viewFile, $data);
				if($j++ < $n-1) {
					echo $this->separator;
				}
			}
		} else {
			$this->renderEmptyText();
		}
		
		echo CHtml::closeTag($this->itemsTagName);
	}
}
