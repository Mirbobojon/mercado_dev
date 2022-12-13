<?php
  require './common/common.php';

  $family_name = '';
  $first_name = '';
  $family_name_furigana = '';
  $first_name_furigana = '';
  $password = '';
  $birthday = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';
  $mail_address = '';
  $mail_magazine_flag = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //formデータの取得
    if(isset($_POST['family_name']))
    {
      $family_name = htmlspecialchars($_POST['family_name'], ENT_QUOTES);
    }
    if(isset($_POST['first_name']))
    {
      $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
    }
    if(isset($_POST['family_name_furigana']))
    {
      $family_name_furigana = htmlspecialchars($_POST['family_name_furigana'], ENT_QUOTES);
    }
    if(isset($_POST['first_name_furigana']))
    {
      $first_name_furigana = htmlspecialchars($_POST['first_name_furigana'], ENT_QUOTES);
    }
    if(isset($_POST['password']))
    {
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    }
    if(isset($_POST['birthday']))
    {
      $birthday = htmlspecialchars($_POST['birthday'], ENT_QUOTES);
    }
    if(isset($_POST['postal_code']))
    {
      $postal_code = htmlspecialchars($_POST['postal_code'], ENT_QUOTES);
    }
    if(isset($_POST['address']))
    {
      $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
    }
    if(isset($_POST['telnumber']))
    {
      $telnumber = htmlspecialchars($_POST['telnumber'], ENT_QUOTES);
    }
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }
    if(isset($_POST['mail_magazine_flag']))
    {
      $mail_magazine_flag = htmlspecialchars($_POST['mail_magazine_flag'], ENT_QUOTES);
    }

    //商品情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($family_name == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$family_name.'\',';
    }

    if($first_name == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$first_name.'\',';
    }

    if($family_name_furigana == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$family_name_furigana.'\',';
    }

    if($first_name_furigana == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$first_name_furigana.'\',';
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

    if($birthday == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$birthday.'\',';
    }

    if($postal_code == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$postal_code.'\',';
    }

    if($address == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$address.'\',';
    }

    if($telnumber == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$telnumber.'\',';
    }

    if($mail_address == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$mail_address.'\',';
    }

    if($mail_magazine_flag == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$mail_magazine_flag.'\'';
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
      $res1 = $JointPrc->callProc('proc_SelectMemberMailaddress',$in1);

      if($res1) {
        // メールアドレスが登録済みの場合
        $ret = false;
        $message = "入力されたメールアドレスは使用できません。";
      }else{
        // メールアドレスが未登録の場合、会員新規追加
        $ret = $JointPrc->callProc('proc_InsertMemberFromAdmin',$param);
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