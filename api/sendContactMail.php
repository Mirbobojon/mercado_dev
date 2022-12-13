<?php
  require './common/common.php';
  // require './mailsend.php';

  $contact_type = '';
  $family_name = '';
  $first_name = '';
  $family_name_furigana = '';
  $first_name_furigana = '';
  $mail_address = '';
  $body = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'sendkey')
  {
    //formデータの取得
    if(isset($_POST['contact_type']))
    {
      $contact_type = htmlspecialchars($_POST['contact_type'], ENT_QUOTES);
    }
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
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address']);
    }
    if(isset($_POST['body']))
    {
      $body = nl2br(htmlspecialchars($_POST['body']));
    }


    //言語と文字コードの使用宣言
    mb_language("uni");
    mb_internal_encoding("UTF-8");

    //管理者へのメール

    // $to = ADMINMAILADDRESS;  //宛先設定(管理者)
    $to = '一括変更（メールアドレス）';  //宛先設定(管理者) //変更必要

    //題名
    $subject = SITENAME.'からのお問い合わせがあります。';

    //本文
    $message  = '';
    $message .= SITENAME.'からお問い合わせがありました。内容は以下の通りです。<br/><br/>';
    $message .= '------------------------------------------------------------<br/>';
    $message .= 'お問い合わせ種類：'.$contact_type.'<br/>';
    $message .= '氏名：'.$family_name.' '.$first_name.'<br/>';
    $message .= 'フリガナ：'.$family_name_furigana.' '.$first_name_furigana.'<br/>';
    $message .= 'メールアドレス：'.$mail_address.'<br/>';
    $message .= 'お問い合わせ内容：'.'<br/>';
    $message .= $body.'<br/>';
    $message .= '------------------------------------------------------------<br/>';

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




    //問い合わせ者へのメール

    $to = $mail_address;  //宛先設定(問い合わせ者)

    //題名
    $subject = 'お問い合わせを承りました。';

    //本文
    $message  = '';
    $message .= $family_name.'様<br/>';
    $message .= '以下の内容でお問い合わせを承りました。<br/><br/>';
    $message .= '------------------------------------------------------------<br/>';
    $message .= 'お問い合わせ種類：'.$contact_type.'<br/>';
    $message .= '氏名：'.$family_name.' '.$first_name.'<br/>';
    $message .= 'フリガナ：'.$family_name_furigana.' '.$first_name_furigana.'<br/>';
    $message .= 'メールアドレス：'.$mail_address.'<br/>';
    $message .= 'お問い合わせ内容：'.'<br/>';
    $message .= $body.'<br/>';
    $message .= '------------------------------------------------------------<br/>';

    //メールヘッダ設定
    $headers = "From: ".ADMINNAME."<".ADMINMAILADDRESS.">";
    $headers  .= "\r\n";
    $headers  .= "Content-Type: text/html; charset=UTF-8";

    //メール送信
    if(mb_send_mail($to,$subject,$message,$headers))
    {
      echo 'mail_send_done';
    }
    else
    {
      echo 'メール送信に失敗しました。';
    }

  }
?>