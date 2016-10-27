<?php

class MySQL_Util
{
	public static function insertQuery($query){
		if (!mysql_query($query)) {
		    return false;
		}

		return true;
	}

	public static function selectQuery($query){
		$result = mysql_query($query);
		if (!$result) {
		    return null;
		}

		return $result;
	}

	public static function executeQuery($query){
		return MySQL_Util::insertQuery($query);
	}

	//mysqlのエラーを取得（文字化けするので変換処理）
	public static function get_mysql_error(){
		return mb_convert_encoding( mysql_error(), "UTF-8", "EUC-JP,SJIS" );
	}

	public static function escape($str){
		return mysql_real_escape_string($str);
	}
}