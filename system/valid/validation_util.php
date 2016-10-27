<?php

require_once(__DIR__.'/../jikkyou.php');

class ValidationUtil
{
	public static function array_get($array, $key, $default=null){
		return Jikkyou::array_get($array, $key, $default);
	}
}