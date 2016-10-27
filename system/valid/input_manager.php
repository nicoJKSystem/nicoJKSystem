<?php
require_once(__DIR__.'/factory_validation.php');

class InputManager
{
	private $config;
	private $error;

	private function array_get($array, $key) {
	 	if(isset($array[$key])){
	 		return $array[$key];
	  	}

  		throw new InvalidArgumentException();
	}

	public function __construct($config){
		$this->setConfig($config);
	}

	public function setConfig($config){
		$this->config = $config;
	}

	public function parseData($arr, $target){
		$data = array();
		$this->error = array();

		foreach ($this->config as $config) {
			$index = $config['key'];

			if(array_key_exists('target', $config)){
				if($config['target'] !== $target){				
					continue;
				}
			}
			try{
				$value = $this->array_get($arr, $index);

				if(mb_strlen($value) == 0) throw new InvalidArgumentException();

				$error = false;
				foreach($config['check'] as $checkData) {
					$valid = FactoryValidation::getValidation($checkData['type'], $checkData['option']);

					if(!$valid->valid($value)){
						$this->error []= $config['name'].$valid::error;
						$error = true;
					}
				}

				if(!$error){
					$data[$index] = $value;
				}
			}catch(InvalidArgumentException $e){
				$this->error []= $config['name'].'が入力されていません';
			}
		}

		return $data;
	}

	public function isError(){
		return count($this->error) > 0;
	}

	public function getError(){
		return $this->error;
	}
}