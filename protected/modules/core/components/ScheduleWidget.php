<?php

class ScheduleWidget extends CWidget
{
	public $hr;
	public $grid;
	public $start;
	public $htmlOptions = array('class' => 'schedule-table');
	public $tableCssClass = 'table table-condensed table-bordered';
	public $dateFormat = 'EEE, d MMM';
	public $nextBtn;
	public $prevBtn;
	public $navParam = 'start';
	public $emptyText;
	public $noHr = false;
	public $dataAction;
	public $dataGetParams;
	public $showSpareTime = false;
	public $showProject = false;
	public $editMode = false;
	
	public function run()
	{
		$options = $this->htmlOptions;
		$options['id'] = $this->id;
		$start = new DateTime($this->start);
		$day = $start->format('j');
		$month = $start->format('n');
		$year = $start->format('Y');
		$format = Yii::app()->format;
		$formatter = Yii::app()->dateFormatter;
		$current_user = Yii::app()->user;
		$today = MysqlDateHelper::currentDate();
		$ts = TaskSchedule::model();
		$working_day_length = $ts->getWorkingDayLength();
		$total_cols = 0;
		$next_date = clone $start;
		$next_date->modify('+1 week');
		$prev_date = clone $start;
		$prev_date->modify('-1 week');
		if (null === $this->prevBtn) {
			$this->prevBtn = '&larr; ' . Yii::t('core.crud', 'Previous week');
		}
		if (null === $this->nextBtn) {
			$this->nextBtn = Yii::t('core.crud', 'Next week') . ' &rarr;';
		}
		$prev_link = $this->buildNavLink($prev_date);
		$next_link = $this->buildNavLink($next_date);
		
		if ($this->noHr) {
			if (isset($options['class'])) {
				$options['class'] .= ' nohr';
			} else {
				$options['class'] = 'nohr';
			}
		}
		
		echo CHtml::openTag('div', $options);
		echo CHtml::openTag('div', array('class' => 'nav row'));
		echo CHtml::openTag('div', array('class' => 'col-xs-6'));
		echo CHtml::link($this->prevBtn, $prev_link, array('class' => 'btn btn-xs btn-default', 'data-ajax-update' => '#' . $this->id));
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div', array('class' => 'col-xs-6 text-right'));
		echo CHtml::link($this->nextBtn, $next_link, array('class' => 'btn btn-xs btn-default', 'data-ajax-update' => '#' . $this->id));
		echo CHtml::closeTag('div');
		echo CHtml::closeTag('div');
		
		echo CHtml::openTag('table', array('class' => $this->tableCssClass));
		echo CHtml::openTag('tr');
		if (!$this->noHr) {
			echo CHtml::tag('th', array('class' => 'h'), '');
			$total_cols++;
		}
		for ($i = 0; $i < 7; $i++) {
			if (!$ts->isWeekend($day + $i, $month, $year)) {
				echo CHtml::tag('th', array('class' => 'g'), CHtml::encode($formatter->format($this->dateFormat, mktime(0,0,0, $month, $day + $i))));
				$total_cols++;
			}
		}
		echo CHtml::closeTag('tr');
		
		if (count($this->grid)) {
			$this->sortGrid();
			foreach ($this->grid as $user => $dates) {
				echo CHtml::openTag('tr');
				if (!$this->noHr) {
					echo CHtml::tag('th', array(), CHtml::encode($this->hr[$user]));
				}
				for ($i = 0; $i < 7; $i++) {
					if (!$ts->isWeekend($day + $i, $month, $year)) {
						$date = MysqlDateHelper::mkdate($day + $i, $month, $year);
						$class_name = array('g');
						$content = '';
						$total = 0;
						$other_tasks = 0;
						$content .= CHtml::openTag('ul');
						if (isset($dates[$date])) {
							foreach ($dates[$date] as $entry) {
								$task = $entry->task;
								if ($current_user->checkAccess('view_task', array('task' => $task))) {
									$task_class_name = 't';
									if ($this->editMode && !$current_user->checkAccess('update_schedule', array('project' => $task->project))) {
										$task_class_name .= ' noupdate';
									}
									$content .= CHtml::openTag('li', array(
										'class' => $task_class_name,
										'data-task' => $task->id,
										'data-task-priority' => $task->priority,
										'data-task-due-date' => $format->formatDate($task->due_date),
										'data-task-estimate' => ViewHelper::formatEstimate($task->getEstimateRange()),
									));
									$content .= CHtml::tag('div', array('class' => 'task-name'), CHtml::link(CHtml::encode(CHtml::value($entry, 'task.name')), array('task/view', 'id' => $entry->task_id), array('title' => CHtml::value($entry, 'task.name'))));
									if ($this->showProject) {
										$content .= CHtml::openTag('div', array('class' => 'task-details project-name'));
										$content .= CHtml::tag('span', array('class' => 'glyphicon glyphicon-briefcase'), '');
										$content .= ' ';
										$content .= CHtml::encode(CHtml::value($task, 'project.name'));
										$content .= CHtml::closeTag('div');
									}
									$content .= CHtml::openTag('div', array('class' => 'task-details'));
									$content .= CHtml::tag('span', array('class' => 'glyphicon glyphicon-time'), '');
									$content .= ' ';
									$content .= Yii::t('core.crud', '{hours} h.', array('{hours}' => ViewHelper::formatDuration($entry->hours)));
									$content .= CHtml::closeTag('div');
									$content .= CHtml::closeTag('li');
									$total += $entry->hours;
								} else {
									$total += $entry->hours;
									$other_tasks += $entry->hours;
								}
							}
						} else {
							$class_name[] = 'empty';
						}
						$free = $working_day_length - $total;
						if ($other_tasks > 0) {
							$content .= CHtml::openTag('li', array(
								'class' => 'other-tasks',
							));
							$content .= Yii::t('core.crud', 'Other tasks: {hours} h.', array('{hours}' => ViewHelper::formatDuration($other_tasks)));
							$content .= CHtml::closeTag('li');
						}
						if ($free > 0 && $this->showSpareTime) {
							$content .= CHtml::openTag('li', array(
								'class' => 'spare-time',
							));
							$content .= Yii::t('core.crud', 'Spare time: {hours} h.', array('{hours}' => ViewHelper::formatDuration($free)));
							$content .= CHtml::closeTag('li');
						}
						echo CHtml::closeTag('ul');
						if ($date == $today) {
							$class_name[] = 'today';
						} elseif (MysqlDateHelper::lt($date, $today)) {
							$class_name[] = 'past';
						}
						if ($total >= $working_day_length) {
							$class_name[] = 'full';
						}
						echo CHtml::openTag('td', array(
							'data-date' => $date,
							'data-user' => $user,
							'class' => implode(' ', $class_name),
						));
						echo $content;
						echo CHtml::closeTag('td');
					}
				}
				echo CHtml::closeTag('tr');
			}
		} else {
			if (null === $this->emptyText) {
				$this->emptyText = Yii::t('core.crud', 'Nothing has been planned for this week');
			}
			echo CHtml::openTag('tr');
			echo CHtml::tag('td', array('class' => 'nothing', 'colspan' => $total_cols), $this->emptyText);
			echo CHtml::closeTag('tr');
		}
		echo CHtml::closeTag('table');
		echo CHtml::closeTag('div');
	}
	
	protected function buildNavLink($date)
	{
		$dataAction = null === $this->dataAction ? $this->controller->action->id : $this->dataAction;
		$link = array($dataAction);
		if (null === $this->dataGetParams) {
			foreach ($_GET as $key => $val) {
				$link[$key] = $val;
			}
		} else {
			foreach ($this->dataGetParams as $key => $val) {
				if (is_numeric($key)) {
					$param = $val;
				} else {
					$param = $key;
					$link[$param] = $val;
				}
				if (isset($_GET[$param])) {
					$link[$param] = $_GET[$param];
				}
			}
		}
		$link[$this->navParam] = $date->format('Y-m-d');
		return $link;
	}
	
	protected function sortGrid()
	{
		$hr = $this->hr;
		uksort($this->grid, function($a, $b) use($hr) {
			$user1 = isset($hr[$a]) ? strval($hr[$a]) : '';
			$user2 = isset($hr[$b]) ? strval($hr[$b]) : '';
			return strcmp($user1, $user2);
		});
	}
}
