<?php
  require './common/common.php';
  $member_id = 12;
  $id = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey')
  {
    if(isset($_SESSION['member_id']))
    {
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);
    }
    //ポストされたデータの取得、代入
    if(isset($_POST['id']))
    {
      $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($member_id == '')
    {
      $param .= 'NULL, ';
    }
    else
    {
      $param .= '\''.$member_id.'\',';
    }

    if($id == '')
    {
      $param .= 'NULL';
    }
    else
    {
      $param .= '\''.$id.'\'';
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
      $ret = $JointPrc->callProc('proc_DeleteDeliveryAddress', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>