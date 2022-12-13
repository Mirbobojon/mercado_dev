<?php
  // require './common/common.php';
  require './mailsend.php';

  $mail_address = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'sendkey')
  {
    //formデータの取得
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }

    //メールアドレスが登録されていないか検索
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($mail_address == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$mail_address.'\'';
    }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_SelectMemberMailaddress',$param);
    }

    if($ret)
    {
      echo 'registered';
    }
    else
    {
      //会員申請テーブルに登録
      //シリアルナンバー作成(40文字)
      $serial = substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, 40);

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

      $param .= '\''.$serial.'\'';

      $ret = $JointPrc->callProc('proc_InsertMemberApplication',$param);

      if($ret)
      {
        //新規会員登録URLのメール
        //パスワード再発行用URLのメール送信
        //言語と文字コードの使用宣言
        mb_language("uni");
        mb_internal_encoding("UTF-8");

        $to = $mail_address;  //宛先設定(申請者)

        //題名
        $title = '新規会員登録申請ありがとうございます。';

        //本文
        $message  = '';
        $message .= '新規会員登録ありがとうございます。以下のURLより会員登録を行ってください。<br/><br/>';
        $message .= '<a href="'.HOMEURL.'signup/'.$serial.'">'.HOMEURL.'signup/'.$serial.'</a>'."<br /><br/>";
        $message .= '会員登録URLの有効期限は30分です。それを過ぎますと発行されたURLは使用できなくなりますので、ご注意ください。';

        //メールヘッダ設定
        $headers = "From: ".ADMINNAME."<".ADMINMAILADDRESS.">";
        $headers  .= "\r\n";
        $headers  .= "Content-Type: text/html; charset=UTF-8";

        //メール送信
        if(mb_send_mail($to,$title,$message,$headers))
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

  }
?>