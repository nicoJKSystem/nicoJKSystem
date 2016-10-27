<?php

function execQuery($config, $query){
	$link = mysql_connect(
			"${config['server']}:${config['port']}",
			$config['user'], $config['pass'])
			or die('MySQL接続失敗: '.MySQL_Util::get_mysql_error());

	if(!mysql_select_db($config['db'], $link)){
		die('データベース選択失敗: '.MySQL_Util::get_mysql_error());
	}

	mysql_query($query);

	if($link == null) return;
	$close_flag = mysql_close($link);

	if ($close_flag){
		// print('<p>切断に成功しました。</p>');
	}
}

function initUserTable($config){
	$query = <<<SQL
CREATE TABLE IF NOT EXISTS `{$config['table']}` (
  `userid` varchar(12) NOT NULL,
  `password` varchar(32) NOT NULL,
  `write_key` varchar(32) DEFAULT NULL,
  `secret_phrase` varchar(32) NOT NULL,
  UNIQUE(`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
	execQuery($config, $query);
}

function initChannelTable($config){
	$query = <<<SQL
CREATE TABLE `{$config['table']}` (
	`name` varchar(32) NOT NULL,
	`id` varchar(32) NOT NULL,
	primary key(`name`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

	execQuery($config, $query);
}
