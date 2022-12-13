<?php
  require './common/common.php';

  $name = '';
  $mail_address = '';
  $password = '';
  $department_id = '';
  $authority_id = '';
  $status = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //formデータの取得
    if(isset($_POST['name']))
    {
      $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    }
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }
    if(isset($_POST['password']))
    {
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    }
    if(isset($_POST['department_id']))
    {
      $department_id = htmlspecialchars($_POST['department_id'], ENT_QUOTES);
    }
    if(isset($_POST['authority_id']))
    {
      $authority_id = htmlspecialchars($_POST['authority_id'], ENT_QUOTES);
    }
    if(isset($_POST['status']))
    {
      $status = htmlspecialchars($_POST['status'], ENT_QUOTES);
    }

    //商品情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($name == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$name.'\',';
    }

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

    $param .= '\''.ENCKEY.'\',';

    if($department_id == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$department_id.'\',';
    }

    if($authority_id == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$authority_id.'\',';
    }

    if($status == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$status.'\'';
    }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      // 登録済みのメールアドレスでないか確認
      $in1 = gen_proc_param([$mail_address]);
      $res1 = $JointPrc->callProc('proc_SelectAdminByMailaddress',$in1);

      if($res1) {
        // メールアドレスが登録済みの場合
        $ret = false;
        $message = "入力されたメールアドレスは使用できません。";
      }else{
        // メールアドレスが未登録の場合、会員新規追加
        $ret = $JointPrc->callProc('proc_InsertAdmin',$param);
        $message = "";
      }

      $send_data = [
        'result' => $ret,
        'message' => $message
      ];
      echo json_encode($send_data);
    }
  }
?>