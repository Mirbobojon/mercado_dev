<?php
  // require './common/common.php';
  require './mailsend.php';

  $send_type = '';
  $destination_type = '';
  $title = '';
  $body = '';
  $status = 'draft';
  $mail_recipients = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //formデータの取得
    if(isset($_POST['send_type']))
    {
      $send_type = htmlspecialchars($_POST['send_type'], ENT_QUOTES);
    }
    if(isset($_POST['destination_type']))
    {
      $destination_type = htmlspecialchars($_POST['destination_type'], ENT_QUOTES);
    }
    if(isset($_POST['title']))
    {
      $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
    }
    if(isset($_POST['body']))
    {
      $body = htmlspecialchars($_POST['body'], ENT_QUOTES);
    }
    if($send_type == 'send')
    {
      $status = 'sended';
    }
    if(isset($_POST['mail_recipients']))
    {
      $mail_recipients = json_decode($_POST['mail_recipients']);
    }


    //メールマガジン情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($destination_type == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$destination_type.'\',';
    }

    if($title == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$title.'\',';
    }

    if($body == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$body.'\',';
    }

    if($status == '')
    {
      $param .= '\'draft\'';
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

      $ret = $JointPrc->callProc('proc_InsertMail',$param);
    }

    if($ret)
    {
      $mail_id = $ret[0]['LAST_INSERT_ID()'];

      //メーリングリストより送信が選択されている場合は宛先メーリングリストの挿入
      if($destination_type == '3')
      {
        for($i=0;$i<count($mail_recipients);$i++)
        {
          //メールマガジン情報の挿入
          //引数（パラメータ）の作成
          $param = '';

          //パラメータの挿入
          if($mail_id == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$mail_id.'\',';
          }
          if($mail_recipients[$i]->id == '')
          {
            $param .= '\'\'';
          }
          else
          {
            $param .= '\''.$mail_recipients[$i]->id.'\'';
          }

          $JointPrc = new JointPrc (); // JointPrc呼び出し部分
          $ret = $JointPrc->callProc('proc_InsertMailRecipient',$param);
        }
      }
    }

    //メール送信
    if($send_type == 'send')
    {
      //メルマガ情報の取得
      //引数（パラメータ）の作成
      $param = '';

      //パラメータの挿入
      if($mail_id == '')
      {
        $param .= '\'\'';
      }
      else
      {
        $param .= '\''.$mail_id.'\'';
      }
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分
      $mail_ret = $JointPrc->callProc('proc_SelectMail',$param);

      if($destination_type == 1)    //メルマガ希望者一斉送信
      {
        //宛先の取得
        $mail_recipient_ret = $JointPrc->callProc('proc_SelectMailRecipientApplicant');
      }
      else if($destination_type == 2)     //会員一斉送信
      {
        //宛先の取得
        $mail_recipient_ret = $JointPrc->callProc('proc_SelectMailRecipientAllMember');
      }
      else if($destination_type == 3)     //メーリングリストより送信
      {
        //宛先の取得
        $mail_recipient_ret = $JointPrc->callProc('proc_SelectMailRecipientMailingList',$param);
      }

      //宛先作成
      if(is_array($mail_recipient_ret))
      {
        //言語と文字コードの使用宣言
        mb_language("uni");
        mb_internal_encoding("UTF-8");

        //メールヘッダ設定
        $headers = "From: ".ADMINNAME."<".ADMINMAILADDRESS.">";
        $headers  .= "\r\n";
        $headers  .= "Content-Type: text/html; charset=UTF-8";

        $recipient_count = count($mail_recipient_ret);
        for($i=0;$i<$recipient_count;$i++)
        {
          $to = $mail_recipient_ret[$i]['mail_address'];  //宛先設定

          $subject = $mail_ret[0]['title'];  //題名設定

          $message = nl2br($mail_ret[0]['body']);  //本文設定

          //メール送信
          mb_send_mail($to,$subject,$message,$headers);
        }
      }
    }

  }
?>