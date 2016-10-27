<?php

class JikkyouTableManager{
	private $mysql;
	private $tableName;

	public function init(){
		$mysql = new MySQL();
		$mysql->init(Config::getJikkyouServer());

		$this->mysql = $mysql;
	}

	public function initTableName($year, $month, $day){
		Config::initTableName($year, $month, $day);
		$this->tableName = Config::getTableName();
	}

	public function getTableName(){
		return $this->tableName;
	}

	//テーブルの作成
	public function makeTable(){
		$tableName = $this->tableName;

		$sql = sprintf(
			"CREATE TABLE `%s` ( `no` int(11) NOT NULL PRIMARY KEY,".
			" `date` varchar(10) NOT NULL,".
			" `userid` varchar(10) NOT NULL,".
			" `comment` text NOT NULL, `channelId` text NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
				$tableName
			);

		$result = MySQL_Util::executeQuery($sql);

		return $result;
	}

	//オートインクリメントの設定
	public function setAutoIncrement($startNum=1){
		$tableName = $this->tableName;

		$sql = sprintf("ALTER TABLE `%s` MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=%d;",
			$tableName, $startNum
		);

		$result = MySQL_Util::executeQuery($sql);

		return $result;
	}

	//テーブルの削除
	public function deleteTable(){
		$tableName = $this->tableName;

		$sql = sprintf(
			"drop TABLE `%s`",
				$tableName
			);

		$result = MySQL_Util::executeQuery($sql);

		return $result;
	}
}