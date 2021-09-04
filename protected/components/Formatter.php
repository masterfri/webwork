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

	public function formatDateFull($value)
	{
		if (empty($value)) {
			return null;
		}
		$timestamp = $this->normalizeDateValue($value);
		return $timestamp ? Yii::t('core.crud', '{m} {d}, {y}', array(
			'{d}' => date('j', $timestamp),
			'{m}' => Yii::t('monthNamesAlt', date('F', $timestamp)),
			'{y}' => date('Y', $timestamp),
		)) : null;
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
		$formatted = number_format($value, 2, $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
		return sprintf('<span class="money-value" data-money-value="%f">%s %s</span>', floatval($value), $formatted, $this->currency);
	}

	public function formatAbstractMoney($value)
	{
		return number_format($value, 2, $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
	}

	public function formatMoneySpellout($value)
	{
		$n = intval($value);
		$c = intval(($value - $n) * 100);

		$result = [];

		if ($n > 0) {
			$result[] = Yii::t('core.crud', '{sum} {cur}', array(
				'{sum}' => $this->formatNumberSpellout($n, Yii::app()->language === 'uk'),
				'{cur}' => Yii::t('core.crud', 'USD|USD|USD|USD', $n),
			));
		}

		$result[] = Yii::t('core.crud', '{csum} {ccur}', array(
			'{csum}' => sprintf('%02d', $c),
			'{ccur}' => Yii::t('core.crud', 'cent|cents|cents|cents', $c),
		));

		return implode(' ', $result);
	}

	public function formatNumberSpellout($value, $feminitive = false)
	{
		if ($value >= 1000000000) {
			$billions = intval($value / 1000000000);
			$rest = $value - $billions * 1000000000;
			return trim(implode(' ', array(
				$this->formatNumberSpellout($billions),
				Yii::t('core.crud', 'billion|billion|billion|billion', $billions),
				$this->formatNumberSpellout($rest, $feminitive),
			)));
		}

		if ($value >= 1000000) {
			$millions = intval($value / 1000000);
			$rest = $value - $millions * 1000000;
			return trim(implode(' ', array(
				$this->formatNumberSpellout($millions),
				Yii::t('core.crud', 'million|million|million|million', $millions),
				$this->formatNumberSpellout($rest, $feminitive),
			)));
		}
		
		if ($value >= 1000) {
			$thousands = intval($value / 1000);
			$rest = $value - $thousands * 1000;
			return trim(implode(' ', array(
				$this->formatNumberSpellout($thousands, true),
				Yii::t('core.crud', 'thousand|thousand|thousand|thousand', $thousands),
				$this->formatNumberSpellout($rest, $feminitive),
			)));
		}

		if ($value >= 100) {
			$hundreds = intval($value / 100);
			$rest = $value - $hundreds * 100;
			$hundredWords = [
				'',
				'one hundred',
				'two hundred',
				'three hundred',
				'four hundred',
				'five hundred',
				'six hundred',
				'seven hundred',
				'eight hundred',
				'nine hundred',
			];
			return trim(implode(' ', array(
				Yii::t('core.crud', $hundredWords[$hundreds]),
				$this->formatNumberSpellout($rest, $feminitive),
			)));
		}		

		if ($value >= 20) {
			$dozens = intval($value / 10);
			$rest = $value - $dozens * 10;
			$dozenWords = [
				'',
				'',
				'twenty',
				'thirty',
				'forty',
				'fifty',
				'sixty',
				'seventy',
				'eighty',
				'ninety',
			];
			return trim(implode(' ', array(
				Yii::t('core.crud', $dozenWords[$dozens]),
				$this->formatNumberSpellout($rest, $feminitive),
			)));
		}

		$words = [
			'',
			'n==0#one|n==1#one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
			'eleven',
			'twelve',
			'thirteen',
			'fourteen',
			'fifteen',
			'sixteen',
			'seventeen',
			'eighteen',
			'nineteen',
		];

		return intval($value === 1) 
			? Yii::t('core.crud', $words[$value], $feminitive ? 1 : 0)
			: Yii::t('core.crud', $words[$value]);
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
