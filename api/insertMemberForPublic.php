<?php
  // require './common/common.php';
  require './mailsend.php';

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
  $serial = '';

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
    if(isset($_POST['serial']))
    {
      $serial = htmlspecialchars($_POST['serial'], ENT_QUOTES);
    }

    //シリアルナンバーとメールアドレスが一致しているか確認
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

      $ret = $JointPrc->callProc('proc_CheckMailaddressAndSerial',$param);
      if($ret)  //シリアルが確認できたら
      {
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

          // 登録済みのメールアドレスでないか念のため確認
          $in2 = gen_proc_param([$mail_address]);
          $res2 = $JointPrc->callProc('proc_SelectMemberMailaddress', $in2);
          if($res2) {
            // メールアドレスが登録済みの場合
            echo 'already_exists';
            exit();
          }

          // 会員情報を登録
          $ret = $JointPrc->callProc('proc_InsertMember',$param);
          // echo json_encode($ret);
        }
        if($ret)
        {
          //新規会員登録完了のメール
          //言語と文字コードの使用宣言
          mb_language("uni");
          mb_internal_encoding("UTF-8");

          $to = $mail_address;  //宛先設定(申請者)

          //題名
          $subject = '新規会員登録ありがとうございます。';

          //本文
          $message  = '';
          $message .= $family_name.$first_name.'様<br/><br/>';
          $message .= 'この度は新規会員登録ありがとうございます。<br/><br/>';
          $message .= '引き続き、一括変更（社名）オンラインショップをお楽しみください。<br/><br/>'; //変更必要
          $message .= '一括変更（社名）オンラインショップ'; //変更必要

          //メールヘッダ設定
          $headers = "From: ".ADMINNAME."<".ADMINMAILADDRESS.">";
          $headers  .= "\r\n";
          $headers  .= "Content-Type: text/html; charset=UTF-8";

          //メール送信
          if(mb_send_mail($to,$subject,$message,$headers))
          {
            echo 'mail_send_done';
          }
          //メール送信に失敗した場合に登録会員情報を削除
          else
          {
            echo 'メール送信に失敗しました。';
          }
        }
      }
      else
      {
        echo 'error';
      }
    }

  }
?>