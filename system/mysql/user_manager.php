<?php

require_once(__DIR__.'/mysql.php');
require_once(__DIR__.'/mysql_util.php');

class UserManager
{
	private $MySQL = null;

	//外部からの入力を変換
	private function convert($index, $value){
		static $config = null;
		if($config === null){
			$config = array(
				'userid' => 'normal',
				'password' => 'md5',
				'write_key' => 'md5',
				'secret_phrase' => 'md5',
			);
		}

		if(array_key_exists($index, $config)){
			switch ($config[$index]) {
				case 'normal':
					return MySQL_Util::escape($value);
				case 'md5':
					return MySQL_Util::escape(md5($value));
				default:
					throw new InvalidArgumentException('変換キーが存在しません');
					break;
			}
		}
	}

	function __construct($MySQL)
	{
		$this->MySQL = $MySQL;
	}

	public function addUser($post){
		$config = $this->MySQL->getConfig();

		return MySQL_Util::insertQuery(
			sprintf("INSERT INTO `%s` (`userid`, `password`, `secret_phrase`) VALUES ('%s', '%s', '%s');",
			$config['table'],
			$this->convert('userid', $post['userid']),
			$this->convert('password', $post['password']),
			$this->convert('secret_phrase', $post['secret_phrase'])
        ));
	}

	private function parseComment($result){
		if($result === null) return array();

		$channelList = array();
		$index = ['userid', 'password'];
		while ($row = mysql_fetch_assoc($result)) {
			$arr = array();
		    
		    foreach ($index as $value) {
			    $arr[$value] = $row[$value];
		    }

			$channelList[] = $arr;
		}

		return $channelList;
	}

	//ユーザーを検索
	public function searchUser($userid){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT * FROM %s WHERE userid='%s'",
				$config['table'],
            	$this->convert('userid', $userid)
			));

		return $this->parseComment($result);
	}

	//ユーザーが存在するかチェック用
	public function isUserExits($userid){
		return count($this->searchUser($userid)) > 0 ? true : false;
	}

	//ユーザーのログイン
	public function login($userid, $password){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT * FROM %s WHERE userid='%s' AND password='%s'",
				$config['table'],
            	$this->convert('userid', $userid),
				$this->convert('password', $password)
			));

		return count($this->parseComment($result)) > 0 ? true : false;
	}

	public function updateWriteKey($userid, $writeKey){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::executeQuery(
			sprintf("UPDATE `%s` SET `write_key`= '%s' WHERE userid='%s'",
				$config['table'],
				$this->convert('write_key', $writeKey),
				$this->convert('userid', $userid)
			));

		return $result;
	}

	public function updatePassword($userid, $password, $secretPhrase){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::executeQuery(
			sprintf("UPDATE `%s` SET `password`= '%s' WHERE userid='%s' AND secret_phrase= '%s'",
				$config['table'],
				$this->convert('password', $password),
				$this->convert('userid', $userid),
				$this->convert('secret_phrase', $secretPhrase)
			));

		return $result;
	}

	public function checkWriteKey($userid, $writeKey){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT count(*) as 'num' FROM %s WHERE userid='%s' AND `write_key`= '%s'",
				$config['table'],
            	$this->convert('userid', $userid),
				$this->convert('write_key', $writeKey)
			));

		$num = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$num = $row['num'];
		}
		return $num > 0 ? true : false;
	}

	public function checkSecretPhrase($userid, $secretPhrase){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT count(*) as 'num' FROM %s WHERE userid='%s' AND secret_phrase= '%s'",
				$config['table'],
            	$this->convert('userid', $userid),
				$this->convert('secret_phrase', $secretPhrase)
			));

		$num = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$num = $row['num'];
		}
		return $num > 0 ? true : false;
	}
}