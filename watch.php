<?php
require_once(__DIR__.'/system/std_include.php');
require_once(__DIR__.'/system/mysql/mysql.php');

if(Jikkyou::init() === FALSE){
	print "初期化に失敗しました。";
	exit(1);
}

$userid = $userStatus['userid'];

$mysql = new MySQL();
$mysql->init(Config::ChannelServer);

$result = $mysql->channelManager->getSearchChannelList($_GET['id']);
if(count($result) != 0){
	$jikkyouName = $result[0]['name'];
}else{
	die('不正なIDです');
}

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- <meta name="viewport" content="width=device-width"> -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="./css/main.css" type="text/css" />
		<link rel="stylesheet" href="./css/navi.css" type="text/css" />

		<link rel="stylesheet" href="./css/commentWindow.css" type="text/css" />

		<script type="text/javascript" src="js/jikkyou/check/ng.js"></script>

		<script type="text/javascript" src="js/jikkyou/check/baseng.js"></script>
		<script type="text/javascript" src="js/jikkyou/check/official_comment_ng.js"></script>
		<script type="text/javascript" src="js/jikkyou/check/comment_ng.js"></script>
		<script type="text/javascript" src="js/jikkyou/check/user_ng.js"></script>

		<script type="text/javascript" src="js/jikkyou/jikkyou.js"></script>
		<script type="text/javascript" src="js/jikkyou/ui.js"></script>
		<script type="text/javascript" src="js/jikkyou/test.js"></script>

		<title><?=$jikkyouName?></title>

		<script type="text/javascript">
		</script>
	</head>
	<body>
		<div id="globalNavi">
			<div id="globalNaviInner">
				<ul class="menu1">
					<li><a href="./index.php">トップページ</a></li>
				</ul>

				<ul class="menu2">
					<li class="username"><?=$userid?>さん ようこそ</li>
				</ul>
				</div>
		</div>
		<div id="mainWindow">
			<div id="leftWindow">
				<div class="wrapper">
					<canvas id="commentView"></canvas>
				</div>
				<div id="commentInput">
					<input type="text" id="comment" class="commentInput" />
					<button type="button" id="postButton" class="commentInput">投稿</button>
				</div>
			</div>

			<div id="ui-tab">
				<ul>
					<li><a href="#fragment-comment"><span>コメント</span></a></li>
					<li><a href="#fragment-system"><span>システム</span></a></li>
				</ul>

				<div id="fragment-comment" class="tabWindowComment">
					<table id="commentTable">
						<thead>
							<th width="10%">時刻</th>
							<th>コメント</th>
							<th width="10%">コメ番</th>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div id="fragment-system" class="tabWindowSystem">

				</div>
			</div>
		</div>

		<div id="comment-dialog" title="NG設定">
		  <!-- <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
		   -->
		   <p></p>
		</div>
	</body>
</html>