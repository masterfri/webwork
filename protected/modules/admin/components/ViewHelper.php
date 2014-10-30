<?php

class ViewHelper
{
	public static function taskPhaseIcon($phase)
	{
		$phases = Task::getListPhases();
		$label = array_key_exists($phase, $phases) ? $phases[$phase] : '';
		$class = '';
		if ($phase == Task::PHASE_CREATED) {
			$class = 'phase-icon phase-new';
		} elseif ($phase == Task::PHASE_SCHEDULED) {
			$class = 'phase-icon phase-scheduled';
		} elseif ($phase == Task::PHASE_IN_PROGRESS) {
			$class = 'phase-icon phase-in-progress';
		} elseif ($phase == Task::PHASE_PENDING) {
			$class = 'phase-icon phase-pending';
		} elseif ($phase == Task::PHASE_NEW_ITERATION) {
			$class = 'phase-icon phase-new-iteration';
		} elseif ($phase == Task::PHASE_CLOSED) {
			$class = 'phase-icon phase-closed';
		} elseif ($phase == Task::PHASE_ON_HOLD) {
			$class = 'phase-icon phase-on-hold';
		}
		return CHtml::tag('span', array('class' => $class, 'title' => $label), '');
	}
	
	public static function taskPriorityLabel($priority)
	{
		if ($priority) {
			$priorities = Task::getListPriorities();
			$label = array_key_exists($priority, $priorities) ? $priorities[$priority] : '';
			$class = '';
			if (Task::PRIORITY_CRITICAL == $priority) {
				$class = 'priority-label priority-critical';
			} elseif (Task::PRIORITY_URGENT == $priority) {
				$class = 'priority-label priority-urgent';
			} elseif (Task::PRIORITY_HIGH == $priority) {
				$class = 'priority-label priority-high';
			} elseif (Task::PRIORITY_MEDIUM == $priority) {
				$class = 'priority-label priority-medium';
			} elseif (Task::PRIORITY_LOW == $priority) {
				$class = 'priority-label priority-low';
			} elseif (Task::PRIORITY_LOWEST == $priority) {
				$class = 'priority-label priority-lowest';
			}
			return CHtml::tag('span', array('class' => $class), $label);
		} else {
			return CHtml::tag('span', array('class' => 'not-set'), Yii::t('admin.crud', 'Not set'));
		}
	}
	
	public static function allPriorityLabels()
	{
		$result = array();
		foreach (Task::getListPriorities() as $priority => $label) {
			$result[$priority] = self::taskPriorityLabel($priority);
		}
		return $result;
	}
	
	public function listTags($tags, $htmlOptions=array()) 
	{
		if (!empty($tags)) {
			$parentTag = 'ul';
			$itemTag = 'li';
			$glue = '';
			if (isset($htmlOptions['parentTag'])) {
				$parentTag = $htmlOptions['parentTag'];
				unset($htmlOptions['parentTag']);
			}
			if (isset($htmlOptions['itemTag'])) {
				$itemTag = $htmlOptions['itemTag'];
				unset($htmlOptions['itemTag']);
			}
			if (isset($htmlOptions['glue'])) {
				$glue = $htmlOptions['glue'];
				unset($htmlOptions['glue']);
			}
			$items = array();
			foreach ($tags as $tag) {
				if (false === $itemTag) {
					$items[$tag->id] = CHtml::encode($tag->name);
				} else {
					$items[$tag->id] = CHtml::tag($itemTag, array(
						'style' => 'background-color: ' . $tag->color,
					), CHtml::encode($tag->name));
				}
			}
			if (false === $parentTag) {
				if (false === $glue) {
					return $items;
				} else {
					return implode($glue, $items);
				}
			} else {
				return CHtml::tag($parentTag, $htmlOptions, implode($glue, $items));
			}
		}
		return '';
	}
	
	public static function formatEstimate($value)
	{
		list($min, $max) = array_map(function($v) {
			if (0 == $v) {
				return '0';
			} elseif ($v >= 20) {
				return strval(round($v));
			} else {
				$v = round($v * 4) / 4;
				return ltrim(strtr($v, array(
					'.25' => '&frac14;',
					'.5' => '&frac12;',
					'.75' => '&frac34;',
				)), '0');
			}
		}, $value);
		
		if ($min !== '0') {
			if ($min == $max) {
				return Yii::t('admin.crud', '{min} hours', array('{min}' => $min));
			} else {
				return Yii::t('admin.crud', '{min} - {max} hours', array('{min}' => $min, '{max}' => $max));
			}
		}
		
		return Yii::t('admin.crud', 'Not available');
	}
}
