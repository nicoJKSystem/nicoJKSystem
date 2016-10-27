<?php
define('SYSTEM_MODE', 'not_check');
require_once(__DIR__.'/../system/std_include.php');

if(!UserUtil::isAdminUser()){
    header("Location: ".'./../index.php');
    exit;
}
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.22/themes/base/jquery-ui.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#add_channel").click(function () {
                $.getJSON("./api/api_add_channel.php", { name: $("#channel_name").val(), id: $("#channel_id").val() }, function(json){

                    var result = (json["info"]["status"] == "200" ? "成功" : "失敗");
                    alert(result + "\n" + json["message"].join("\n"));
                });
            });

            $("#add_table").click(function () {
                 $.getJSON("./api/api_add_table.php", { count: $("#table_count").val() }, function(json){
                    var result = (json["info"]["status"] == "200" ? "成功" : "失敗");
                    alert(result + "\n" + json["message"].join("\n"));
                });
            });
        });

    </script>
</head>

<h3>■管理機能</h3>
※サーバーによってはタイムアウトするので注意

<ul>
	<li>チャンネル登録
		<div>
			チャンネル名(1～32文字)<input type="text" id="channel_name" /><br>
			チャンネルID 半角英数(1～32文字)<input type="text" id="channel_id" /><br>
			<button id="add_channel">実行</button>
		</div>
	</li>
	<li>テーブル作成
		<div>
			本日から何日分テーブルを作成するか？(1～999)<input type="text" id="table_count" /><br>
			<button id="add_table">実行</button>
		</div>
	</li>
	<li><a href="./../">トップページ</a></li>
</ul>

