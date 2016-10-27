<?php
//ログイン時
define('SYSTEM_MODE', "login");
require_once(__DIR__.'/../system/std_include.php');
require_once(__DIR__.'/../system/user/user_form_manager.php');

$userFormManager = new UserFormManager();
$userFormManager->initToken();
$userFormManager->setMode(UserFormManager::LOGIN);
?>
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ユーザーログイン</title>
</head>

<body>
<?php 
$errors = Jikkyou::array_get_once($_SESSION, 'error', array());
if(count($errors) > 0){ ?>
ログインに失敗しました。
<?php } ?>

<form action="./login_check.php" method="post">
<input type="hidden" name="token" value="<?= $userFormManager->getToken(); ?>"> 
ユーザーID：<input type="text" name="userid" value=""><br>
パスワード：<input type="password" name="password" value=""><br>
<input type="submit" value="データを送信">
</form>
</body>
