<?php

require_once(__DIR__.'/channel_manager.php');
require_once(__DIR__.'/comment_manager.php');
require_once(__DIR__.'/user_manager.php');
require_once(__DIR__.'/mysql_util.php');

class MySQL
{
	private $link = null;
	private $config = null;
	public $channelManager = null;
	public $commentManager = null;
	public $userManager;

	public function getConfig(){
		return $this->config;
	}

	public function init($config){
		$this->config = $config;

		$this->link = mysql_connect(
			"${config['server']}:${config['port']}",
		 	$config['user'], $config['pass']) 
		or die('MySQL接続失敗: '.MySQL_Util::get_mysql_error());

		if(!mysql_select_db($config['db'], $this->link)){
			die('データベース選択失敗: '.MySQL_Util::get_mysql_error());
		}
		
		mysql_set_charset('utf8');

		$channelManager = new ChannelManager($this);
		$this->channelManager = $channelManager;

		$this->commentManager = new CommentManager($this);
		$this->userManager = new UserManager($this);
	}

	public function close(){
		if($this->link == null) return;
		$close_flag = mysql_close($this->link);

		if ($close_flag){
		    // print('<p>切断に成功しました。</p>');
		}
	}

	public function __destruct() {
    	$this->close();
  	}
}