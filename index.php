<?php
define('SYSTEM_MODE', 'not_redirect');
require_once(__DIR__.'/system/std_include.php');
require_once(__DIR__.'/system/mysql/mysql.php');

if(Jikkyou::init() === FALSE){
	print "初期化に失敗しました。";
	exit(1);
}

$userStatus = UserUtil::getStatus();
$mysql = new MySQL();
$mysql->init(Config::ChannelServer);

$list = $mysql->channelManager->getChannelList();
?>

<?php
if($userStatus['login']){
$userid = $userStatus['userid'];
?>
こんにちは<?=$userid?>さん
<?php }?>

<h3>■チャンネル</h3>
<ul>
<?php foreach ($list as $item){ ?>
<li><a href="./watch.php?id=<?=$item['id']?>"><?=$item['name']?></a></li>
<?php } ?>
</ul>

<h3>■ユーザー</h3>
<ul>
<?php if(!$userStatus['login']){ ?>
<li><a href="./user/useradd.php">ユーザー登録</a></li>
<li><a href="./user/login.php">ユーザーログイン</a></li>
</ul>
<?php }else { ?>
<li><a href="./user/logout.php">ログアウト</a></li>
<?php }?>
</ul>

<h3>■管理</h3>
<ul>
<li><a href="./admin/">管理者ページ</a></li>
</ul>