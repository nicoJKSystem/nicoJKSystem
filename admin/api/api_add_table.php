<?php
define('SYSTEM_MODE', 'not_check');
require_once(__DIR__.'./../../system/std_include.php');
require_once(__DIR__.'./../../system/mysql/mysql.php');
require_once(__DIR__.'./../../system/mysql/jikkyou_table_manager.php');
require_once(__DIR__.'./../../system/valid/input_manager.php');

$json = array(
	'info'=>array(
		'status' => Data::STATUS['succeed']
	),
	'message' => array()
);

try{
	Jikkyou::init();
	if(!UserUtil::isAdminUser()){
		$json['message'][]= '管理ユーザーではありません';
		throw new InvalidArgumentException();
	}

	//指定されていなければ本日の日付を取得
	$startTimeArray = Jikkyou::getTimeArray();
	$year = Jikkyou::array_get($_GET, 'year', $startTimeArray['year']);
	$month = Jikkyou::array_get($_GET, 'month', $startTimeArray['month']);
	$base_day = Jikkyou::array_get($_GET, 'day', $startTimeArray['day']);

	$inputManager = new InputManager([
		array('key' => 'count', 'name' => '日数', 'check' => [
			array('type' => 'num', 'option' => array()),
			array('type' => 'length', 'option' => array('min' => 1, 'max' => 3))
		])
	]);

	$input = $inputManager->parseData($_GET, '');

	if($inputManager->isError()){
		$json['message'] = array_merge($json['message'], $inputManager->getError());
		throw new InvalidArgumentException('入力が不正です。');
	}

	$max = Jikkyou::array_get($_GET, 'count', 7);

	$json['message'][] = sprintf("%s年%s月%s日から%s日分テーブルを作成します。\n", $year, $month, $base_day, $max);

	$table_manager = new JikkyouTableManager();
	$table_manager->init();

	function getMessagae($flag, $srcmessage, $message){
		return $srcmessage.($flag ? $message[0] : $message[1]);
	}

	//当日から$count分テーブルを作成(デフォルトは7日)
	for($base_offset=0,$count=0; $count < $max; $count++){
		$offset = $base_offset + $count;

		$timeArray = Jikkyou::getTimeArray($year, $month, $base_day + $offset);
		$table_manager->initTableName($timeArray['year'], $timeArray['month'], $timeArray['day']);
		$tableName = $table_manager->getTableName();

		$result = $table_manager->makeTable();
		$json['message'][] = getMessagae($result, $tableName.' テーブルの作成に', ['成功しました。', '失敗しました。'.MySQL_Util::get_mysql_error()]);

		//テーブルの作成に成功したら（失敗したら、テーブルが存在する）
		if($result){
			$result = $table_manager->setAutoIncrement();
			$json['message'][] = getMessagae($result, $tableName.' オートインクリメントの設定に', ['成功しました。', '失敗しました。'.MySQL_Util::get_mysql_error()]);
		}

		// $result = $table_manager->deleteTable();
		// $json['message'][] = getMessagae($result, $tableName.' テーブルの削除に', ['成功しました。', '失敗しました。'.MySQL_Util::get_mysql_error()]);
	}
} catch (InvalidArgumentException $e) {
	$json['info']['status'] = Data::STATUS['arg_error'];
} catch (RuntimeException $e){
	$json['info']['status'] = Data::STATUS['server_error'];
}

header('Content-Type: application/json; charset=utf-8');
print json_encode($json);