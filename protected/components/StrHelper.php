<?php

class StrHelper
{
	public static function generateRandomString($len=10)
	{
		$alpha = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$l = strlen($alpha);
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$str .= $alpha[rand(0, $l - 1)];
		}
		return $str;
	}
}