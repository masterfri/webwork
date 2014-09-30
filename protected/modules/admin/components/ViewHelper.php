<?php

class ViewHelper
{
	public static function taskPhaseIcon($task)
	{
		$class = '';
		if ($task->phase == Task::PHASE_CREATED) {
			$class = 'color-lightblue glyphicon glyphicon-asterisk';
		} elseif ($task->phase == Task::PHASE_SCHEDULED) {
			$class = 'color-coral glyphicon glyphicon-user';
		} elseif ($task->phase == Task::PHASE_IN_PROGRESS) {
			$class = 'color-blue glyphicon glyphicon-play';
		} elseif ($task->phase == Task::PHASE_PENDING) {
			$class = 'color-red glyphicon glyphicon-flag';
		} elseif ($task->phase == Task::PHASE_NEW_ITERATION) {
			$class = 'color-yellow glyphicon glyphicon-repeat';
		} elseif ($task->phase == Task::PHASE_CLOSED) {
			$class = 'color-green glyphicon glyphicon-ok';
		} elseif ($task->phase == Task::PHASE_ON_HOLD) {
			$class = 'color-grey glyphicon glyphicon-pause';
		}
		return CHtml::tag('span', array('class' => $class, 'title' => $task->getPhase()), '');
	}
	
	public static function taskPriorityLabel($task)
	{
		if ($task->priority) {
			$class = '';
			if (Task::PRIORITY_CRITICAL == $task->priority) {
				$class = 'label label-danger';
			} elseif (Task::PRIORITY_URGENT == $task->priority) {
				$class = 'label label-warning';
			} elseif (Task::PRIORITY_HIGH == $task->priority) {
				$class = 'label label-primary';
			} elseif (Task::PRIORITY_MEDIUM == $task->priority) {
				$class = 'label label-info';
			} elseif (Task::PRIORITY_LOW == $task->priority) {
				$class = 'label label-success';
			} elseif (Task::PRIORITY_LOWEST == $task->priority) {
				$class = 'label label-default';
			}
			return CHtml::tag('span', array('class' => $class), $task->getPriority());
		} else {
			return CHtml::tag('span', array('class' => 'not-set'), Yii::t('admin.crud', 'Not set'));
		}
	}
}
