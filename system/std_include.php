<?php
require_once(__DIR__.'/data.php');

session_start();
defined('SYSTEM_MODE') or define('SYSTEM_MODE', 'normal');

switch (SYSTEM_MODE) {
	case 'useradd':
	case 'logincheck':
	case 'login':
		Data::changeLoginMode();
		break;
	case 'not_redirect':
		Data::changeTopPageMode();
		break;
	case 'normal':
		break;
	case 'not_check':
		Data::notLoginCheckMode();
		break;
}

require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/jikkyou.php');
require_once(__DIR__.'/user_util.php');
require_once(__DIR__.'/stdio.php');

$userStatus = UserUtil::getStatus();

function pageProcess($userStatus)
{
	$loginConfig = Data::getLoginConfig();

	if(!$loginConfig['check']) return;

	$page = '';
	if($userStatus['login']){
		if($loginConfig['redirect_logined']){
			$page = $loginConfig['logined_page'];
			exit;
		}
	}else{
		if($loginConfig['redirect_not_login']){
			$page = $loginConfig['not_login_page'];		
		}
	}
	if($page !== ''){
		header("Location: ".$page);
		exit;
	}
}

pageProcess($userStatus);