<?php
class Data
{
	const SYSTEM_VER = '01';
	const STATUS = array(
		'succeed' => 200,
		'arg_error' => 400,
		'server_error' => 500
	);

	private static $loginConfig = array(
		'check' => true,
		'redirect_logined' => false,	//ログイン中ならリダイレクトするか
		'redirect_not_login' => true,	//ログイン中でないならリダイレクトするか
		'logined_page' => './../index.php',	//ログイン中なら移動するページ
		'not_login_page' => './user/login.php'	//ログイン中でないなら移動するページ
	);

	//ログインが失敗したとき無限ループしないように(ログインページ用)
	public static function changeLoginMode()
	{
		self::$loginConfig['redirect_logined'] = true;
		self::$loginConfig['redirect_not_login'] = false;
	}

	//トップページ用
	public static function changeTopPageMode()
	{
		self::$loginConfig['redirect_logined'] = false;
		self::$loginConfig['redirect_not_login'] = false;
	}

	//ログインが失敗したとき無限ループしないように(ログインページ用)
	public static function notLoginCheckMode()
	{
		self::$loginConfig['check'] = false;
	}

	public static function getLoginConfig(){
		return self::$loginConfig;
	}
}
?>