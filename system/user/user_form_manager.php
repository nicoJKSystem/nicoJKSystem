<?php
class UserFormManager
{
	const LOGIN = 'login';
	const USERADD = 'useradd';
	const SETPASSWORD = 'setpassword';

	private $error;
	function __construct()
	{
		$this->error = array();
	}

	function initToken(){
		if (!isset($_SESSION['token'])) {
    		$_SESSION['token'] = base64_encode(mt_rand());
		}
	}

	function checkToken($token){
		return $_SESSION['token'] === $token;
	}

	function getToken(){
		return $_SESSION['token'];
	}

	function setMode($mode){
		$_SESSION['mode'] = $mode;
	}

	function initError(){
		$_SESSION['error'] = array();
	}

	function addError($error){
		if(gettype($error) === "array"){
			$this->error = array_merge($this->error, $error);
		}else{
			$this->error [] = $error;
		}
	}

	function getError(){
		return $this->error;
	}

	function addErrorWithException($error, $exception){
		$this->addError($error);
		throw $exception;
	}
}