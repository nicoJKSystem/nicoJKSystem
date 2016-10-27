<?php
//ログイン時
define('SYSTEM_MODE', "logincheck");
require_once(__DIR__.'/../../system/std_include.php');
require_once(__DIR__.'/../../user/config.php');

require_once(__DIR__.'/../../system/user/user_form_manager.php');
require_once(__DIR__.'/../../system/mysql/mysql.php');
require_once(__DIR__.'/../../system/stdio.php');
require_once(__DIR__.'/../../system/user_util.php');
require_once(__DIR__.'/../../system/valid/input_manager.php');

require_once(__DIR__.'/createFirstTable.php');

$pageInfoData = array(
	UserFormManager::USERADD => array(
		'successPage' => './../',
		'errorPage' => './index.php'
	)
);

try {
	$userFormManager = new UserFormManager();
	$inputManager = new InputManager($inputConfig);

	// $userFormManager->initError();

	$token = Jikkyou::array_get($_POST, 'token');
	$mode = Jikkyou::array_get($_SESSION, 'mode');

	$pageInfo = Jikkyou::array_get($pageInfoData, $mode);

	if(!$userFormManager->checkToken($token)){
		$userFormManager->addErrorWithException('トークンが違います。', new RuntimeException('サーバー内部エラー'));
	}

	$_POST['userid'] = UserUtil::getAdminUserName();
	$input = $inputManager->parseData($_POST, $mode);

	if($inputManager->isError()){
		$userFormManager->addErrorWithException($inputManager->getError(),
		 new InvalidArgumentException('入力が不正です。'));
	}

	Jikkyou::init();

	//テーブルの初期化
	initUserTable(Config::UserServer);
	initChannelTable(Config::ChannelServer);

	$mysql = new MySQL();
	$mysql->init(Config::UserServer);

	$userid = $input['userid'];
	$password = $input['password'];

	switch ($mode) {
		case UserFormManager::USERADD:
			//ユーザーが存在しないなら追加
			if(!$mysql->userManager->isUserExits($userid)){
				$mysql->userManager->addUser($input);
				$_SESSION['success'] = 'ユーザー作成に成功しました。';
			}else{
				$userFormManager->addErrorWithException('ユーザーがすでに存在しています',
					new InvalidArgumentException('ユーザーがすでに存在しています'));
			}
			break;
		default:
			break;
	}
}catch(Exception $e){
	$_SESSION['error'] = $userFormManager->getError();
	$_SESSION['errorType'] = array($e->getMessage());
	// $_SESSION['errorClass'] = get_class($e);
	header("Location: ".$pageInfo['errorPage']);
	exit;
}

UserUtil::setValue('userid', $userid);
UserUtil::setValue('login', true);
UserUtil::exportSession();

header("Location: ".$pageInfo['successPage']);
exit;