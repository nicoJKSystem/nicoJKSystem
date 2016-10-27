<?php

class RegexValidation implements Validation
{
	const error = 'がパターンに一致しません';

	protected $pattern;

	function __construct($option){
		$this->init($option['regex']);
	}

	public function init($pattern){
		$this->pattern = $pattern;
	}

	public function valid($input){
		if (preg_match($this->pattern, $input)) {
			return true;
		}else
			return false;
	}
}