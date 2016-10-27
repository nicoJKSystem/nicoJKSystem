<?php

require_once(__DIR__.'/mysql.php');
require_once(__DIR__.'/mysql_util.php');

class CommentManager
{
	private $MySQL = null;

	function __construct($MySQL)
	{
		$this->MySQL = $MySQL;
	}

	public function addComment($post){
		$config = $this->MySQL->getConfig();

		return MySQL_Util::insertQuery(
			sprintf("INSERT INTO `%s` (`date`, `userid`, `comment`, `channelId`) VALUES ('%s', '%s', '%s', '%s' );",
			$config['table'],
			MySQL_Util::escape($post['date']),
			MySQL_Util::escape($post['userid']),
			MySQL_Util::escape($post['comment']),
			$post['channelId']
		));
	}

	private function parseComment($result){
		if($result === null) return array();

		$channelList = array();
		$index = ['no', 'date', 'userid', 'comment'];
		while ($row = mysql_fetch_assoc($result)) {
			$arr = array();
		    
		    foreach ($index as $value) {
			    $arr[$value] = $row[$value];
		    }

			$channelList[] = $arr;
		}

		return $channelList;
	}

	public function searchCommentAfterNo($id, $no){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT * FROM %s WHERE channelId='%s' AND no >= '%s'",
				$config['table'],
            	MySQL_Util::escape($id),
				$no
			));

		return $this->parseComment($result);
	}

	public function searchCommentFromLast($id, $num){
		$config = $this->MySQL->getConfig();

		$result = MySQL_Util::selectQuery(
			sprintf("SELECT * FROM %s WHERE channelId='%s' order by no DESC LIMIT 0, %s",
				$config['table'],
            	MySQL_Util::escape($id),
            	$num
			));

		return $this->parseComment($result);
	}
}