<?php
define('SYSTEM_MODE', "useradd");
require_once(__DIR__.'/../system/std_include.php');
require_once(__DIR__.'/../system/user/user_form_manager.php');

$userFormManager = new UserFormManager();
$userFormManager->initToken();
$userFormManager->setMode(UserFormManager::USERADD);
?>

<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ユーザー登録</title>
</head>

<body>

<?php
$errors = Jikkyou::array_get_once($_SESSION, 'error', array());
foreach ($errors as $item){ ?>
<li><a href=""><?=$item?></a></li>
<?php } ?>

<form action="./login_check.php" method="post">
<input type="hidden" name="token" value="<?= $userFormManager->getToken(); ?>"> 
ユーザーID(半角英数4～10文字)：<input type="text" name="userid" value=""><br>
パスワード(半角英数8～12文字)：<input type="password" name="password" value=""><br>
パスフレーズ(8～12文字)：<input type="password" name="secret_phrase" value=""><br>
<input type="submit" value="登録">
</form>
</body>
