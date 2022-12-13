<?php
  require './common/common.php';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'checkkey')
  {
    //セッションにログイン情報が入っているかチェック
    if(!isset($_SESSION['admin_id']))
    {
      echo 'nologin';
    }
    else
    {
      $admin_id = $_SESSION['admin_id'];
      //引数（パラメータ）の作成
      $param = '';
      if($admin_id == '')
      {
        $param .= 'NULL';
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
        $ret = $JointPrc->callProc('proc_SelectAdmin',$param);
        if(!empty($ret))
        {
          echo json_encode($ret);
        }
        else
        {
          echo 'error';
        }
      }
    }
  }
?>