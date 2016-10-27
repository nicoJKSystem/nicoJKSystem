<?php
session_start();
$_SESSION = array();
session_destroy();
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}

$topPage = './../index.php';
?>

<html>
<head>
	<title>ログアウトしました。</title>
	<meta http-equiv="refresh" content="5;URL=<?=$topPage?>">
</head>
<body>
ログアウトしました。
5秒後に自動的にトップページへ戻ります。<br>
戻らない場合は、下記のＵＲＬをクリックしてください。<br>
<br>
<a href="<?=$topPage?>">トップページへ</a>
</body>
</html>
