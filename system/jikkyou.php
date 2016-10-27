<?php

class Jikkyou
{
	//サーバー設定の初期化
	public static function init(){
		$result = true;

		//タイムゾーンの設定
		$result = date_default_timezone_set('Asia/Tokyo') ? : $result = false;

		//日本語ロケールの設定
		// setlocale(LC_ALL, 'ja_JP.UTF-8') ? : $result = false;
		
		//内部文字エンコーディングをUTF-8に設定
		mb_internal_encoding('UTF-8') ? : $result = false;
		
		if($result === false)
			throw new RuntimeException("初期化に失敗しました。");

		return $result;
	}

	//現在時刻を配列にして返す
	//年月日を指定すると現在の時刻の年月日を変更した時刻を返す
	public static function getTimeArray($year=null, $month=null, $day=null){
		$datetime = new DateTime();
		if($year != null && $month != null && $day != null){
			$datetime->setDate($year, $month, $day);
		}

		$index = ['year', 'month', 'day', 'hour', 'min', 'sec'];
		
		$tmp = explode('-', $datetime->format('Y-m-d-H-i-s'));
		$arr = array();

		foreach ($tmp as $key => $value) {
			$arr[$index[$key]] = $value;
		}

		return $arr;
	}

	//指定された日付("YYYY-MM-DD")から現在時刻までの差分を返す最初の6文字(HH:MM:SS)残り3文字ミリ秒
	public static function getCommentTime($startDate){
		$interval = (new DateTime())->diff(new DateTime($startDate));
		$tmp = explode(' ', $interval->format('%d %h %i %s'));
		$milli_time = (int)(explode(" ", microtime())[0]*1000);
		
		return sprintf('%02s%02s%02s%03s',
			$tmp[0]*24 + $tmp[1], $tmp[2], $tmp[3], $milli_time );
	}

	public static function array_get(&$array, $key, $default=null, $option=array()) {
	 	if(isset($array[$key])){
	 		if(in_array('unset', $option)){
	 			//配列だったらメモリコピーされるのでいつもはしない
	 			$value = $array[$key];
	  			unset($array[$key]);
	  			return $value;
	 		}
	  		return $array[$key];
	  	}

	  	//デフォルト値が指定されていないなら例外
	  	if($default === null)
  			throw new InvalidArgumentException($key.'が入力されていません');
  		else
  			return $default;
	}

	public static function array_get_once(&$array, $key, $default=null) {
	 	return Jikkyou::array_get($array, $key, $default, array('unset'));
	}
	
}
