<?php
  require './common/common.php';
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  $current_dir = dirname(__FILE__);
  require($current_dir . '/PHPMailer/src/Exception.php');
  require($current_dir . '/PHPMailer/src/PHPMailer.php');
  require($current_dir . '/PHPMailer/src/SMTP.php');

  date_default_timezone_set('Asia/Tokyo');
  mb_language('japanese');
  mb_internal_encoding('utf-8');

  function mail_send($to_array, $subject_value, $body_value)
  {

    if(is_array($to_array))
    {
      $count = count($to_array);
      for($i=0;$i<$count;$i++)
      {
        $mail = new PHPMailer();
        try{

          // ＜SMTPで送信する場合＞
          // Enable SMTP debugging (デバッグ出力させない場合は 0)
          // 0 = off (for production use)
          // 1 = client messages
          // 2 = client and server messages
          $mail->SMTPDebug = 0;

          // SMTP
          $mail->isSMTP();

          // hostname
          $mail->Host = 'smtp.gmail.com';

          // SMTP port (587)
          $mail->Port = 587;

          // 暗号化 (ssl|tls)
          $mail->SMTPSecure = 'tls';

          // SMTP認証
          $mail->SMTPAuth = true;

          // ユーザー名
          $mail->Username = '一括変更（メールアドレス）'; //変更必要

          // パスワード
          $mail->Password = '';

          //文字コード
          $mail->CharSet = 'UTF-8';

          // オプション
          $mail->SMTPOptions = [
            'ssl' => [
              'verify_peer' => false
              ,'verify_peer_name' => false
              ,'allow_self_signed' => true
            ]
          ];
          // ＜/ SMTPで送信する場合＞

          // From address
          $mail->setFrom(ADMINMAILADDRESS, ADMINNAME);

          // Reply-to address
          $mail->addReplyTo(ADMINMAILADDRESS, ADMINNAME);

          // 件名
          $mail->Subject = $subject_value;

          // 本文 HTMLを無効 (PLAINのみ)
          $mail->isHTML(true);
          $mail->Body = $body_value;

          // X-Mailer を非表示にする
          $mail->XMailer = null;

          // To address
          $mail->addAddress($to_array[$i]['mail_address'], $to_array[$i]['family_name']);

          // 送信
          $mail->send();

          // return true;
        }
        catch(Exception $e)
        {
          return false;
        }

      }
    }
  }
?>