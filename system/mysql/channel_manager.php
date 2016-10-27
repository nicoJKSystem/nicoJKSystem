<?php

require_once(__DIR__.'/mysql.php');
require_once(__DIR__.'/mysql_util.php');

class ChannelManager
{
	private $MySQL = null;

	function __construct($MySQL)
	{
		$this->MySQL = $MySQL;
	}

	public function addChannel($channel){
		$config = $this->MySQL->getConfig();

		return MySQL_Util::insertQuery(
			sprintf("INSERT INTO ${config['table']} (name, id) VALUES ('%s', '%s')",
         		MySQL_Util::escape($channel['name']),
          	 	MySQL_Util::escape($channel['id'])));
	}

	private function parseChannel($result){
		if($result === null) return array();

		$channelList = array();
		while ($row = mysql_fetch_assoc($result)) {
			$arr = array();
		    $arr['name'] = $row['name'];
			$arr['id'] = $row['id'];

			$channelList[] = $arr;
		}

		return $channelList;
	}

	public function getChannelList(){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery("SELECT * FROM ${config['table']}");
		return $this->parseChannel($result);
	}

	public function getSearchChannelList($id){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT * FROM ${config['table']} WHERE id='%s'",
            	MySQL_Util::escape($id)));

		return $this->parseChannel($result);
	}
}