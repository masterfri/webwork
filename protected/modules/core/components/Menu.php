<?php

Yii::import('zii.widgets.CMenu');

class Menu extends CMenu
{
	protected function normalizeItems($items,$route,&$active)
	{
		foreach($items as $i => $item) {
			if(isset($item['visible']) && !$item['visible']) {
				unset($items[$i]);
				continue;
			}
			if(!isset($item['label'])) {
				$item['label'] = '';
			}
			if($this->encodeLabel) {
				$items[$i]['label'] = CHtml::encode($item['label']);
			}
			if (isset($item['counter']) && $item['counter'] > 0) {
				$items[$i]['label'] .= ' <span class="badge">' . $item['counter'] . '</span>';
			}
			$hasActiveChild = false;
			if(isset($item['items'])) {
				$items[$i]['items'] = $this->normalizeItems($item['items'], $route, $hasActiveChild);
				if(empty($items[$i]['items']) && $this->hideEmptyItems) {
					unset($items[$i]['items']);
					if(!isset($item['url']) || '#' == $item['url']) {
						unset($items[$i]);
						continue;
					}
				}
			}
			if(!isset($item['active'])) {
				if($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item, $route)) {
					$active=$items[$i]['active'] = true;
				} else {
					$items[$i]['active'] = false;
				}
			} elseif($item['active']) {
				$active = true;
			}
		}
		return array_values($items);
	}
	
	protected function renderMenuItem($item)
	{
		$addon = '';
		if (isset($item['addon'])) {
			$addon = CHtml::link($item['addon']['label'], $item['addon']['url'], isset($item['addon']['options']) ? $item['addon']['options'] : array());
		}
		if (isset($item['url'])) {
			$label = $this->linkLabelWrapper === null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
			return $addon . CHtml::link($label, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
		} else {
			return $addon . CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
		}
	}
}
