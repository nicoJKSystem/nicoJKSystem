<?php

class LengthValidation implements Validation
{
	const error = 'が文字列の指定の長さと一致しません';

	private $min;
	private $max;
	
	function __construct($option){
		$this->min = ValidationUtil::array_get($option, 'min', 0);
		$this->max = $option['max'];
	}

	public function valid($input){
		$length = mb_strlen($input);

		if($this->min <= $length && $length <= $this->max){
			return true;
		}else
			return false;
	}
}