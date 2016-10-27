<?php
require_once(__DIR__.'/config.php');

require_once(__DIR__.'/system/data.php');
require_once(__DIR__.'/system/jikkyou.php');
require_once(__DIR__.'/system/mysql/mysql.php');

$result = array(
	'info'=>array(
		'last_no' => -1,
		'ver' => Data::SYSTEM_VER,
		'status' => Data::STATUS['succeed'],
	)
);

try {
	session_start();
	$channelId = Jikkyou::array_get($_GET, 'channel_id');
	$read_from = intval(Jikkyou::array_get($_GET, 'read_from'));

	Jikkyou::init();

	$startTimeArray = Jikkyou::getTimeArray();

	Config::initTableName(
		$startTimeArray['year'], $startTimeArray['month'], $startTimeArray['day']);

	$mysql = new MySQL();
	$mysql->init(Config::getJikkyouServer());

	if($read_from >= 1){
		$list = $mysql->commentManager->searchCommentAfterNo($channelId, $read_from);
	}else if($read_from < 0){
		$read_num = -$read_from;
		if($read_num > 1000) $read_num = 1000;

		//結果の行を反転
		$list = array_reverse($mysql->commentManager->searchCommentFromLast($channelId, $read_num));
	}

	$result['info']['last_no'] = count($list) == 0 ? -1 : $list[count($list) - 1]['no'];
	$result['chat']=$list;
} catch (InvalidArgumentException $e) {
	$result['info']['status'] = Data::STATUS['arg_error'];
} catch (RuntimeException $e){
	$result['info']['status'] = Data::STATUS['server_error'];
}

// header('Content-Type: text/javascript; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');
print json_encode($result);

?>