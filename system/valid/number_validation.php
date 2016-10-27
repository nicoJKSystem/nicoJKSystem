<?php

class NumberValidation extends RegexValidation
{
	const error = 'が全部数字ではありません';
	function __construct()
	{	
		$this->init('/^[\d]+$/');
	}
}