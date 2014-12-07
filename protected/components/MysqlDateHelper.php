<?php

class MysqlDateHelper
{
	const EMPTY_DATE = '0000-00-00';
	const EMPTY_DATETIME = '0000-00-00 00:00:00';
	
	public static function isEmpty($date)
	{
		return '' == $date || self::EMPTY_DATE == $date || self::EMPTY_DATETIME == $date;
	}
	
	public static function currentDate()
	{
		return date('Y-m-d');
	}
	
	public static function currentDatetime()
	{
		return date('Y-m-d H:i:s');
	}
	
	public static function compare($date1, $date2)
	{
		if (self::isEmpty($date1) && self::isEmpty($date2)) {
			return 0;
		}
		if (self::isEmpty($date1)) {
			return 1;
		}
		if (self::isEmpty($date2)) {
			return -1;
		}
		$dif = self::diff($date1, $date2);
		return 0 == $dif ? 0 : ($dif > 0 ? 1 : -1);
	}
	
	public static function gt($date1, $date2)
	{
		return -1 == self::compare($date1, $date2);
	}
	
	public static function lt($date1, $date2)
	{
		return 1 == self::compare($date1, $date2);
	}
	
	public static function diff($date1, $date2)
	{
		if (self::isEmpty($date1) || self::isEmpty($date2)) {
			return 0;
		}
		$datetime = new DateTime($date1);
		$interval = $datetime->diff(new DateTime($date2));
		return false === $interval ? 0 : intval($interval->format('%R%a'));
	}
}
