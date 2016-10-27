<?php
class UserUtil{
	//変更を書き込むバッファ
	private static $changeData = array();

	const dataMap = array(
		array( 'access_index' => 'login',	'session_index' => 'login', 'type' => 'boolean', 'default' => false),
		array( 'access_index' => 'userid', 	'session_index' => 'userid', 'type' => 'string'),
	);

	private static function is_session_started()
	{
	    if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === '' ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	}

	public static function getValue($key, $default=''){
		$value = $default;

		foreach (self::dataMap as $data) {
			if(!($data['access_index'] === $key)) continue;

			$session_index = $data['session_index'];

			//存在するキーだけ調べる
			if(array_key_exists($session_index, $_SESSION)){
				$type = gettype($_SESSION[$session_index]);
				if($type === $data['type']){
					$value = $_SESSION[$session_index];
					break;
				}else{
					throw new InvalidArgumentException(
						sprintf('変数の型が違います。入力キー %s 期待される型 %s 実際の型 %s',
						 $data['access_index'], $data['type'], $type));
				}
			}else{
				if(isset($data['default']))
					$result[$data['access_index']] = $data['default'];
			}
		}

		return $value;
	}

	public static function getStatus(){
		$result = array();

		foreach (self::dataMap as $data) {
			$session_index = $data['session_index'];

			//存在するキーだけ調べる
			if(array_key_exists($session_index, $_SESSION)){
				$type = gettype($_SESSION[$session_index]);
				if($type === $data['type']){
					$result[$data['access_index']] = $_SESSION[$session_index];
				}else{
					throw new InvalidArgumentException(
						sprintf('変数の型が違います。入力キー %s 期待される型 %s 実際の型 %s',
						 $data['access_index'], $data['type'], $type));
				}
			}else{
				if(isset($data['default']))
					$result[$data['access_index']] = $data['default'];
			}
		}

		return $result;
	}

	//一時的なバッファに書き込む
	public static function setStatus($status){
		foreach ($status as $key => $value) {
			self::setValue($key, $value);
		}
	}

	public static function setValue($key, $value){
		foreach (self::dataMap as $data) {
			if($key === $data['access_index']){
				$type = gettype($value);
				if($type === $data['type']){
					self::$changeData[$data['session_index']] = $value;
				}else{
					throw new InvalidArgumentException(
						sprintf('変数の型が違います。入力キー %s 期待される型 %s 実際の型 %s',
						 $key, $data['type'], $type));
				}
			}
		}
	}

	//変更されたデータをセッションに格納
	public static function exportSession(){
		$_SESSION = array_merge($_SESSION, self::$changeData);
	}

	//管理ユーザか
	public static function isAdminUser(){
		$status = self::getStatus();
		if($status['login'] && $status['userid'] === self::getAdminUserName()) return true;

		return false;
	}

	//管理ユーザ名
	public static function getAdminUserName(){
		return 'admin';
	}
}

// session_start();
// var_dump($_SESSION);
// var_dump(UserUtil::getStatus());
// UserUtil::setValue('userid', 'hiyoko');
// UserUtil::exportSession();
?>