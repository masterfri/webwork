<?php

class Formatter extends CFormatter
{
	public $currency = 'USD';
	
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
	
	public function formatMoney($value)
	{
		return number_format($value, 2, $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']) . ' ' . $this->currency;
	}
	
	public function formatNumber($value)
	{
		if ($this->numberFormat['decimals'] > 0) {
			return rtrim(rtrim(parent::formatNumber($value), '0'), $this->numberFormat['decimalSeparator']);
		} else {
			return parent::formatNumber($value);
		}
	}
	
	protected function normalizeDateValue($time)
	{
		if (is_string($time)) {
			if (ctype_digit($time) || ($time{0}=='-' && ctype_digit(substr($time, 1)))) {
				return (int) $time;
			} elseif ('0000-00-00' != $time && '0000-00-00 00:00:00' != $time) {
				return strtotime($time);
			} else {
				return 0;
			}
		}
		return (int) $time;
	}
}
