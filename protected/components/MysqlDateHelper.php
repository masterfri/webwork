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
	
	public static function mkdate($d, $m=null, $y=null)
	{
		return date('Y-m-d', mktime(0,0,0, $m === null ? date('m') : $m, $d, $y === null ? date('Y') : $y));
	}
	
	public static function mktime($h, $i, $s=0, $d=null, $m=null, $y=null)
	{
		return date('Y-m-d H:i:s', mktime($h, $i, $s, $m === null ? date('m') : $m, $d === null ? date('d') : $d, $y === null ? date('Y') : $y));
	}
	
	public static function compare($date1, $date2, $skip_time=true)
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
		$dif = self::diff($date1, $date2, $skip_time);
		return 0 == $dif ? 0 : ($dif > 0 ? 1 : -1);
	}
	
	public static function gt($date1, $date2, $skip_time=true)
	{
		return -1 == self::compare($date1, $date2, $skip_time);
	}
	
	public static function lt($date1, $date2, $skip_time=true)
	{
		return 1 == self::compare($date1, $date2, $skip_time);
	}
	
	public static function diff($date1, $date2, $skip_time=true)
	{
		if (self::isEmpty($date1) || self::isEmpty($date2)) {
			return 0;
		}
		$datetime = new DateTime($date1);
		$interval = $datetime->diff(new DateTime($date2));
		return false === $interval ? 0 : intval($interval->format($skip_time ? '%R%a' : '%R%a%H%I%S'));
	}
}
