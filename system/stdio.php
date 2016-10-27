<?php

function getLineSeparator(){
	return php_sapi_name() === 'cli' ? "\n" : "<br>";
}

function puts($string){
	static $br = null;
	if($br === null)
		$br = getLineSeparator();

	print $string.$br;
}

?>