<?php
  require './common/common.php';
  $mailing_list_title = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['mailing_list_title']))
    {
      $mailing_list_title = htmlspecialchars($_POST['mailing_list_title'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($mailing_list_title == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$mailing_list_title.'\'';
    }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      //呼び出し
      $ret = $JointPrc->callProc('proc_SelectSearchMailingList', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>