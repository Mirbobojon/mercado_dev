<?php
  // require './common/common.php';
  require './mailsend.php';

  $mail_address = '';
  $serial = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'sendkey')
  {
    //formデータの取得
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }

    //シリアルナンバー作成(40文字)
    $serial = substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, 40);

    //メールアドレスが登録されていないか検索
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($serial == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$serial.'\',';
    }

    //管理者フラグに0を挿入
    $param .= '\'0\',';

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

      // 該当会員が存在するかどうか確認
      $in1 = gen_proc_param([$mail_address]);
      $res1 = $JointPrc->callProc('proc_SelectMemberMailaddress', $in1);

      // 該当会員が存在しない場合
      if(empty($res1)) {
        echo "not_registered";
        exit();
      }
      
      $ret = $JointPrc->callProc('proc_InsertReissue',$param);
    }

    if($ret)
    {
      //パスワード再発行用URLのメール送信
      //言語と文字コードの使用宣言
      mb_language("uni");
      mb_internal_encoding("UTF-8");
      //送信内容作成

      $to = $mail_address;  //宛先設定(申請者)

      //題名
      $subject = 'パスワード再発行用URL';

      //本文
      $message  = '';
      $message .= 'パスワード再発行申請を承りました。以下のURLより再設定を行ってください。<br/><br/>';
      $message .= '<a href="'.HOMEURL.'reissue/'.$serial.'">'.HOMEURL.'reissue/'.$serial.'</a>'."<br /><br/>";
      $message .= '再設定URLの有効期限は30分です。それを過ぎますと発行されたURLは使用できなくなりますので、ご注意ください。';

      //メールヘッダ設定
      $headers = "From: ".ADMINNAME."<".ADMINMAILADDRESS.">";
      $headers  .= "\r\n";
      $headers  .= "Content-Type: text/html; charset=UTF-8";

      //メール送信
      if(mb_send_mail($to,$subject,$message,$headers))
      {
        echo 'true';
      }
      else
      {
        echo 'メール送信に失敗しました。';
      }

    }
    else
    {
      echo 'error';
    }

  }
?>