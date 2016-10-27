<?php
require_once(__DIR__.'/config.php');

require_once(__DIR__.'/system/data.php');
require_once(__DIR__.'/system/jikkyou.php');
require_once(__DIR__.'/system/mysql/mysql.php');

$result = array(
	'info' => array(
		'ver' => Data::SYSTEM_VER,
		'status' => Data::STATUS['succeed'],
		'message' => ''
	),
);

function checkWriteKey($userid, $writeKey) {
	$mysql = new MySQL();
	$mysql->init(Config::UserServer);

	return $mysql->userManager->checkWriteKey($userid, $writeKey);
}

try{
	session_start();
	$channelId = Jikkyou::array_get($_GET, 'channel_id');
	$comment = Jikkyou::array_get($_GET, 'comment');
	$writeKey = Jikkyou::array_get($_GET, 'writeKey');

	Jikkyou::init();
	if(!checkWriteKey($_SESSION['userid'], $writeKey)){
		throw new InvalidArgumentException();
	}

	$startTimeArray = Jikkyou::getTimeArray();

	Config::initTableName(
		$startTimeArray['year'], $startTimeArray['month'], $startTimeArray['day']);

	$mysql = new MySQL();
	$mysql->init(Config::getJikkyouServer());

	$succeed = $mysql->commentManager->addComment(array(
		'userid'=>$_SESSION['userid'],
		'date'=>Jikkyou::getCommentTime(
				sprintf("%s-%s-%s", $startTimeArray['year'], $startTimeArray['month'], $startTimeArray['day'])
			),
		'comment'=>$comment,
		'channelId'=>$channelId
	));

	if($succeed === FALSE){
		$result['info']['status'] = Data::STATUS['server_error'];
		// $result['info'] =
	}

} catch (InvalidArgumentException $e) {
	$result['info']['status'] = Data::STATUS['arg_error'];
} catch (RuntimeException $e){
	$result['info']['status'] = Data::STATUS['server_error'];
}

// header('Content-Type: text/javascript; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');
print json_encode($result);
?>