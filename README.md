ニコニコ実況の簡易版を目指して作成しました。

実況機能をできるだけ、簡単にwebサイトに設置できるよう
PHPと古いmysqlのAPIで作ってあります。

・開発環境
XAMPP Version: 5.6.14
PHP 5.6.14 (cli)
MySQL Ver 15.1 Distrib 10.1.8-MariaDB
Windows 7

PC版
Chrome,Firefox

・使用方法
①データベースを作成して、ユーザーを用意してデータベースに対する権限を与えておきます。

②config.phpでサーバーの情報を変更します。

例
下の５個の内ポート番号以外を入力
//データベース名
const DB_NAME = 'jikkyou';

//データベースサーバーのアドレス
const SERVER = 'localhost';

//データベースサーバーのポート
const SERVER_PORT = '3306';

//データベースのログインユーザー
const DB_USER = 'jikkyou';

//データベースのログインパスワード
const DB_PASS = 'jikkyou';

③サーバアドレス/jikkyou/admin/setup/にアクセスして
パスワードやパスフレーズを入力

④管理ページにて
チャンネルを追加
データ保存用の日付テーブルを追加（時間がかかるとタイムアウトするので注意！）
※日付用のテーブルがないとコメントが保存されません。

⑤トップページからチャンネルへアクセス

各ファイルは機能を参照ください。
・機能
現段階　完了しているもの
コメントの取得　書き込み
チャンネル取得　追加
日ごとに変わる実況テーブル　追加処理
ユーザー機能（ログイン、書き込みキーなど）
管理ページ

・フォルダ構造
-ルートフォルダー

基本アクセスされるページ
■index.php トップページ
■channel.php チャンネルの追加・読みこみ
■jikkyou_table_manager.php テーブルの追加

■post.php コメント書き込み
channel_id チャンネルID
comment コメント
writeKey 書き込みキー（getwritekey.phpで取得）
http://localhost/jikkyou/post.php?comment=test&channel_id=test&writeKey=OTA1NjM3ODIz

■thread.php コメント読み込み(jsonで返す)　
channel_id チャンネルID
readfrom 1以上の場合コメントno以降を表示　負の場合　一番最新からさかのぼる数 例はjk1の最新10件を取得
http://localhost/jikkyou/thread.php?read_from=-10&channel_id=jk1

設定など
config.php サーバーのデータベースの設定

-system システム部
	data.php 定数など
	jikkyou.php システムに関するユーティリティ
		-mysql MySQL
			channel_manager.php　チャンネルに関する管理
			comment_manager.php コメントに関する管理
			jikkyou_table_manager	実況テーブル管理
			mysql.php　データベースに接続
			mysql_util.php ユーティリティ
			user_manager.php ユーザーに関する管理

-admin
	index.php 管理ページ
	-api
		api_add_channel.php	チャンネルの追加API
		api_add_table.php	テーブルの追加API
	-setup
		index.php 管理ユーザー設定ページ