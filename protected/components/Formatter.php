<?php

class Formatter extends CFormatter
{
	public $currency = 'USD';
	public $excerptMaxLength = 500;
	public $excerptMinLength = 350;
	public $excerptEnding = '...';
	
	public function formatDate($value)
	{
		if (empty($value)) {
			return null;
		}
		$timestamp = $this->normalizeDateValue($value);
		return $timestamp ? date($this->dateFormat, $timestamp) : null;
	}

	public function formatTime($value)
	{
		if (empty($value)) {
			return null;
		}
		$timestamp = $this->normalizeDateValue($value);
		return $timestamp ? date($this->timeFormat, $timestamp) : null;
	}

	public function formatDatetime($value)
	{
		if (empty($value)) {
			return null;
		}
		$timestamp = $this->normalizeDateValue($value);
		return $timestamp ? date($this->datetimeFormat, $timestamp) : null;
	}
	
	public function formatArray($value)
	{
		if (empty($value)) {
			return null;
		}
		return is_array($value) ? implode(', ', $value) : $value;
	}
	
	public function formatHours($value)
	{
		$hours = intval($value);
		$minutes = round(60 * ($value - $hours));
		return sprintf('%d:%02d', $hours, $minutes);
	}
	
	public function formatSeconds($value)
	{
		$minutes = intval($value / 60);
		$seconds = $value % 60;
		if ($minutes > 0) {
			return Yii::t('core.crud', '{m} min, {s} sec', array(
				'{m}' => $minutes,
				'{s}' => $seconds,
			));
		}
		return Yii::t('core.crud', '{s} sec', array(
			'{s}' => $seconds,
		));
	}
	
	public function formatMoney($value)
	{
		return number_format($value, 2, $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']) . ' ' . $this->currency;
	}
	
	public function formatBalance($value)
	{
		if ($value > 0) {
			return sprintf('<span class="positive-balance">%s</span>', $this->formatMoney($value));
		} elseif ($value < 0) {
			return sprintf('<span class="negative-balance">%s</span>', $this->formatMoney(-$value));
		} else {
			return '';
		}
	}
	
	public function formatNumber($value)
	{
		if ($this->numberFormat['decimals'] > 0) {
			return rtrim(rtrim(parent::formatNumber($value), '0'), $this->numberFormat['decimalSeparator']);
		} else {
			return parent::formatNumber($value);
		}
	}
	
	public function formatExcerpt($value)
	{
		$value = strip_tags($value);
		if (mb_strlen($value) > $this->excerptMaxLength) {
			$value = mb_substr($value, 0, $this->excerptMaxLength);
			$stops = array('.', '?', '!', ' ');
			foreach ($stops as $stop) {
				$pos = strrpos($value, $stop, $this->excerptMinLength);
				if ($pos !== false) {
					$value = substr($value, 0, $pos);
					break;
				}
			}
			$value .= $this->excerptEnding;
		}
		return CHtml::encode($value);
	}
	
	protected function normalizeDateValue($time)
	{
		if (is_string($time)) {
			if (ctype_digit($time) || ($time{0}=='-' && ctype_digit(substr($time, 1)))) {
				return (int) $time;
			} elseif (!MysqlDateHelper::isEmpty($time)) {
				return strtotime($time);
			} else {
				return 0;
			}
		}
		return (int) $time;
	}
	
	public function parseHours($value)
	{
		if (strpos($value, ':') !== false) {
			list($h, $m) = explode(':', $value);
			return $h + $m / 60;
		} elseif (strpos($value, 'h') !== false || strpos($value, 'm') !== false) {
			if (preg_match_all("/(\d+)\s*([hm])/", $value, $matches, PREG_SET_ORDER)) {
				$total = 0;
				foreach ($matches as $match) {
					if ($match[2] == 'h') {
						$total += $match[1];
					} elseif ($match[2] == 'm') {
						$total += $match[1] / 60;
					}
				}
				return $total;
			} else {
				return floatval($value);
			}
		} else {
			return floatval($value);
		}
		return 0;
	}
}
