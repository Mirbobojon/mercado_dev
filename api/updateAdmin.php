<?php
  require './common/common.php';

  $admin_id = '';
  $name = '';
  $mail_address = '';
  $department_id = '';
  $authority_id = '';
  $status = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_POST['admin_id']))
    {
      $admin_id = htmlspecialchars($_POST['admin_id'], ENT_QUOTES);
    }
    if(isset($_POST['name']))
    {
      $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    }
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
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
    if($admin_id == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$admin_id.'\',';
    }

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
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$mail_address.'\',';
    }

    if($department_id == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$department_id.'\',';
    }

    if($authority_id == '')
    {
      $param .= 'NULL,';
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
        if($res1[0]['id'] != $admin_id) {
          // メールアドレスが別の管理者で使用中の場合
          $ret = false;
          $message = "入力されたメールアドレスは使用できません。";
        }else{
          // メールアドレスが自分でのみ使用されている（更新しない）場合、管理者情報更新
          $ret = $JointPrc->callProc('proc_UpdateAdmin',$param);
          $message = "";
        }
      } else {
        // メールアドレスが別の管理者で使用されていない場合、管理者情報更新
        $ret = $JointPrc->callProc('proc_UpdateAdmin',$param);
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