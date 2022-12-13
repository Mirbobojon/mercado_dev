<?php

//開発環境 ここから//

// DB接続情報定義
define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "mercado_dev_db"); //変更必要

define("HOMEDIR", "http://localhost:3000/mercado_dev/react/"); //変更必要

define("REACTDIR", "/react");
define("IMAGEPATH", HOMEDIR."/images");
define("NEWSDIR", IMAGEPATH."/news");
define("NEWSTEMPDIR", IMAGEPATH."/news_temp");
define("UPDATENEWSTEMPPATH", "../../");
define("UPDATEITEMTEMPPATH", "../../");
define("ITEMDIR", IMAGEPATH."/items");
define("UPDATEITEMSDIRTEMPPATH", "../images/items");

define("ENCKEY", "enckey1234567890");

//管理者情報
define("HOMEURL", "http://localhost:3000/mercado_dev/react/"); //変更必要
define("ADMINURL", "http://localhost:3000/mercado_dev/react/admin/"); //変更必要
define("MYPAGEURL", "http://localhost:3000/mercado_dev/react/mypage/"); //変更必要
define("ADMINMAILADDRESS", "一括変更（メールアドレス）"); //変更必要
// define("MAILADDRESS", "一括変更（メールアドレス）"); //変更必要
define("ADMINNAME", "一括変更（社名） ECサイト管理者"); //変更必要
define("SITENAME", "一括変更（社名） ECサイト"); //変更必要


//開発環境 ここまで//


//オンライン決済

//Squareテスト決済環境
define("REQUESTURL", "https://connect.squareupsandbox.com/v2/payments"); /* 変更必要 */
define("LOCATIONID", "L9PS7Y0F82N8F"); /* 変更必要 */
define("BEARERTOKEN", "EAAAELH4VYQPOO7S45lrpPMAUoNCMzoo8RFbz4o9tob03ezLLnv0DJR2tMjWCt1p"); /* 変更必要 */

?>