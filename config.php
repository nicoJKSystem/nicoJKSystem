<?php

class Config
{
	//データベース名
	const DB_NAME = 'jikkyou';

	//データベースサーバーのアドレス
	const SERVER = 'localhost';

	//データベースサーバーのポート
	const SERVER_PORT = '3306';

	//データベースのログインユーザー
	const DB_USER = 'jikkyou';

	//データベースのログインパスワード
	const DB_PASS = 'jikkyou';

	const ChannelServer = array(
		'server' => Config::SERVER,
		'port' => Config::SERVER_PORT,
		'user' => Config::DB_USER,
		'pass' => Config::DB_PASS,
		'db' => Config::DB_NAME,
		'table' => 'channel'
	);

	const UserServer = array(
		'server' => Config::SERVER,
		'port' => Config::SERVER_PORT,
		'user' => Config::DB_USER,
		'pass' => Config::DB_PASS,
		'db' => Config::DB_NAME,
		'table' => 'user'
	);

	const JikkyouServerTemplate = array(
		'server' => Config::SERVER,
		'port' => Config::SERVER_PORT,
		'user' => Config::DB_USER,
		'pass' => Config::DB_PASS,
		'db' => Config::DB_NAME,
		'tableFormat' => 'jikkyou_%s'
	);

	private static $tableName;

	/**
		実況サーバー情報を取得
	*/
	public static function getJikkyouServer(){
		$Jikkyou = Config::JikkyouServerTemplate;
		$Jikkyou['table'] = self::$tableName;
		return $Jikkyou;
	}

	public static function initTableName($year, $month, $day){
		self::$tableName = sprintf(Config::JikkyouServerTemplate['tableFormat'], $year.$month.$day);
	}

	public static function getTableName(){
		return self::$tableName;
	}
}

?>