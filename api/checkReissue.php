<?php
  require './common/common.php';
  $serial = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'checkkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['serial']))
    {
      $serial = htmlspecialchars($_POST['serial'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($serial == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$serial.'\'';
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
      $ret = $JointPrc->callProc('proc_CheckReissu', $param);

      if($ret)
      {
        date_default_timezone_set('Asia/Tokyo');
        $insert_datetime = new DateTime($ret[0]['insert_datetime']);
        $now = new DateTime();
        if($insert_datetime < $now->modify('-30 minute'))
        {
          echo 'overtime';
        }
        else
        {
          echo $ret[0]['mail_address'];
        }
      }
      else
      {
        return;
      }
    }

  }


?>