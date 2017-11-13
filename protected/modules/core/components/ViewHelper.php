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
		} elseif ($phase == Task::PHASE_COMPLETED) {
			$class = 'phase-icon phase-completed';
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
			return CHtml::tag('span', array('class' => 'not-set'), Yii::t('core.crud', 'Not set'));
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
	
	public static function formatDuration($value)
	{
		$value = round($value * 4) / 4;
		return ltrim(strtr($value, array(
			'.25' => '&frac14;',
			'.5' => '&frac12;',
			'.75' => '&frac34;',
		)), '0');
	}
	
	public static function formatEstimate($value)
	{
		list($min, $max) = array_map(function($v) {
			if (0 == $v) {
				return '0';
			} elseif ($v >= 20) {
				return strval(round($v));
			} else {
				return ViewHelper::formatDuration($v);
			}
		}, $value);
		
		if ($min !== '0') {
			if ($min == $max) {
				return Yii::t('core.crud', '{min} hours', array('{min}' => $min));
			} else {
				return Yii::t('core.crud', '{min} - {max} hours', array('{min}' => $min, '{max}' => $max));
			}
		}
		
		return Yii::t('core.crud', 'Not available');
	}
	
	public static function progerss($total, $done) 
	{
		$width = $total > 0 ? round(100 * $done / $total) : 0;
		$html  = CHtml::openTag('span', array('class' => 'progress-indicator'));
		$html .= CHtml::tag('span', array('class' => 'progress-indicator-bar', 'style' => sprintf('width: %d%%;', $width)), '');
		$html .= CHtml::openTag('span', array('class' => 'progress-indicator-numbers'));
		$html .= CHtml::tag('span', array('class' => 'count-done'), $done); 
		$html .= '/';
		$html .= CHtml::tag('span', array('class' => 'count-total'), $total); 
		$html .= CHtml::closeTag('span');
		$html .= CHtml::closeTag('span');
		return $html;
	}
	
	public static function miniGraph($data, $padding=1, $showOvergrow=true, $additionalClass='')
	{
		$html = '';
		if (count($data) > 0) {
			$html = sprintf('<div class="mini-graph %s">', CHtml::encode($additionalClass));
			$width = 100 / count($data);
			$max = max($data);
			$max = max($max, $padding);
			$i = 0;
			foreach ($data as $value) {
				$label = Yii::t('core.crud', '{hours} h.', array('{hours}' => self::formatDuration($value)));
				$html .= sprintf('<div class="v-bar" title="%s" style="width: %s%%">', $label, $width);
				if ($value > $padding && $showOvergrow) {
					$html .= sprintf('<div style="height: %s%%;" class="v-val overgrown"></div>', 100 * $value / $max);
					$html .= sprintf('<div style="height: %s%%;" class="v-val"></div>', 100 * $padding / $max);
				} else {
					$html .= sprintf('<div style="height: %s%%;" class="v-val"></div>', 100 * $value / $max);
				}
				$html .= '</div>';
				$i++;
			}
			$html .= '</div>';
		}
		return $html;
	}
}
