<?php
$inputConfig = [
	array('key' => 'userid', 'name' => 'ユーザーＩＤ', 'check' => [
		array('type' => 'alphabet_num', 'option' => array()),
		array('type' => 'length', 'option' => array('min' => 4, 'max' => 12))
	]),
	array('key' => 'password', 'name' => 'パスワード', 'check' => [
		array('type' => 'alphabet_num', 'option' => array()),
		array('type' => 'length', 'option' => array('min' => 8, 'max' => 12))
	]),

	array('target' => 'useradd', 'key' => 'secret_phrase', 'name' => 'パスフレーズ：', 'check' => [
		array('type' => 'length', 'option' => array('min' => 8, 'max' => 12))
	])
];