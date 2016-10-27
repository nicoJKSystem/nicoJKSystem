<?php
require_once(__DIR__.'/stdio.php');

use STDIO\PUTS;
// use STDIO\PUTS as Another;

$SYSTEM_PROPERTY = array();

$SYSTEM_PROPERTY['line.separator'] = (php_sapi_name() === 'cli' ? "\n" : "<br>");

function getLineSeparator(){
	return php_sapi_name() === 'cli' ? "\n" : "<br>";
}

function puts($string){
	static $ins = null;
	global $SYSTEM_PROPERTY; 
	if($ins === null)
	 	$ins = new PUTS();

	// $ins->puts($string);
	 // print($string).$SYSTEM_PROPERTY['line.separator'];

	static $br = null;
	if($br === null)
		$br = getLineSeparator();

	print $string.$br;
}

puts("AA");
puts("AA");

?>