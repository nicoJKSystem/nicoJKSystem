<?php

class AlphabetNumberValidation extends RegexValidation
{
	const error = 'が全部アルファベットか数字ではありません';
	function __construct()
	{	
		$this->init('/^[a-zA-Z\d]+$/');
	}
}