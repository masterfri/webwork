<?php

class Formatter extends CFormatter
{
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
}
