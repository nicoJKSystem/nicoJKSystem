<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/system/data.php');
require_once(__DIR__.'/system/jikkyou.php');
require_once(__DIR__.'/system/mysql/mysql.php');
require_once(__DIR__.'/system/stdio.php');
require_once(__DIR__.'/system/user_util.php');

if(Jikkyou::init() === FALSE){
	print "初期化に失敗しました。";
	exit(1);
}

$result = array(
	'info'=>array(
		'writeKey' => '',
		'ver' => Data::SYSTEM_VER,
		'status' => Data::STATUS['succeed'],
	)
);

session_start();

$mysql = new MySQL();
$mysql->init(Config::UserServer);

$userStatus = UserUtil::getStatus();
$writeKey = base64_encode(mt_rand());

if($userStatus['login']){
	$userid = $userStatus['userid'];
	$mysql->userManager->updateWriteKey($userid, $writeKey);
	$result['info']['writeKey'] = $writeKey;
}else{
	$result['info']['status'] = Data::STATUS['arg_error'];
}

header('Content-Type: application/json; charset=utf-8');
print json_encode($result);