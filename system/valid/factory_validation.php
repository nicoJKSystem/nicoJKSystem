<?php

require_once(__DIR__.'/validation.php');
require_once(__DIR__.'/validation_util.php');
require_once(__DIR__.'/regex_validation.php');
require_once(__DIR__.'/number_validation.php');
require_once(__DIR__.'/alphabet_number_validation.php');
require_once(__DIR__.'/length_validation.php');

class FactoryValidation
{
	public static function getValidation($type, $option){
		switch ($type) {
			case 'alphabet_num':
				return new AlphabetNumberValidation();
			case 'num':
				return new NumberValidation();
			case 'regex':
				return new RegexValidation($option);
			case 'length':
				return new LengthValidation($option);
			default:
				throw new InvalidArgumentException('チェック指定が不正です。');			
		}
	}
}