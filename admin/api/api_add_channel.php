<?php
define('SYSTEM_MODE', 'not_check');
require_once(__DIR__.'./../../system/std_include.php');
require_once(__DIR__.'./../../system/mysql/mysql.php');
require_once(__DIR__.'./../../system/valid/input_manager.php');

$result = array(
	'info'=>array(
		'status' => Data::STATUS['succeed']
	),
	'message' => array()
);

try{
	Jikkyou::init();

	if(!UserUtil::isAdminUser()){
		$result['message'][]= '管理ユーザーではありません';
		throw new InvalidArgumentException();
	}

	$inputManager = new InputManager([
		array('key' => 'name', 'name' => 'チャンネル名', 'check' => [
			array('type' => 'length', 'option' => array('min' => 1, 'max' => 32))
		]),
		array('key' => 'id', 'name' => 'チャンネルID', 'check' => [
			array('type' => 'alphabet_num', 'option' => array()),
			array('type' => 'length', 'option' => array('min' => 1, 'max' => 32))
		])
	]);

	$input = $inputManager->parseData($_GET, '');

	if($inputManager->isError()){
		$result['message'] = array_merge($result['message'], $inputManager->getError());
		throw new InvalidArgumentException('入力が不正です。');
	}

	$mysql = new MySQL();
	$mysql->init(Config::ChannelServer);

	$mysql->channelManager->addChannel(array(
		'name' => $input['name'],
		'id' => $input['id']
	));

	$result['message'][] = 'チャンネルを作成しました。';
} catch (InvalidArgumentException $e) {
	$result['info']['status'] = Data::STATUS['arg_error'];
} catch (RuntimeException $e){
	$result['info']['status'] = Data::STATUS['server_error'];
}

header('Content-Type: application/json; charset=utf-8');
print json_encode($result);