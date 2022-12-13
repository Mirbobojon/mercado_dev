<?php
  require './common/common.php';
  $admin_id = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['admin_id']))
    {
      $admin_id = htmlspecialchars($_POST['admin_id'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($admin_id == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$admin_id.'\'';
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
      $ret = $JointPrc->callProc('proc_DeleteAdmin', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>