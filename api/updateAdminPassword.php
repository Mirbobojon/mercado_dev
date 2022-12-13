<?php
  require './common/common.php';

  $mail_address = '';
  $serial = '';
  $password = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }
    if(isset($_POST['serial']))
    {
      $serial = htmlspecialchars($_POST['serial'], ENT_QUOTES);
    }
    if(isset($_POST['password']))
    {
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    }

    //メールアドレスとシリアルナンバーが正しいか確認

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

      $ret = $JointPrc->callProc('proc_CheckSerialMailAddress',$param);
      if(!empty($ret))
      {
        // echo json_encode($ret);

        //パスワードの変更
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

        $ret = $JointPrc->callProc('proc_UpdateAdminPassword',$param);
        echo json_encode($ret);

        // パスワード再発行用シリアルを削除
        $admin_flag = 1;  /* 管理者 */
        $in3 = gen_proc_param([$mail_address, $serial, $admin_flag]);
        $res3 = $JointPrc->callProc('proc_DeleteReissueSerial', $in3);
      }
      else
      {
        echo 'error';
      }
    }
  }
?>