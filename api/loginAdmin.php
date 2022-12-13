<?php
  require './common/common.php';

  $mail_address = '';
  $password = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'loginkey')
  {
    //formデータの取得
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }
    if(isset($_POST['password']))
    {
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    }

    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($mail_address == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$mail_address.'\',';
    }
    if($password == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$password.'\',';
    }

    $param .= '\''.ENCKEY.'\'';

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_LoginAdmin',$param);
      if($ret)
      {
        //セッション情報に管理者ログイン情報を挿入
        $_SESSION['admin_id'] = $ret[0]['id'];
        echo json_encode($ret);
      }
      else
      {
        echo 'error';
      }
    }
  }
?>