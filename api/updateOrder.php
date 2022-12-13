<?php
  // require './common/common.php';
  require './mailsend.php';

  $order_id = '';
  $status = '';
  $member_id = '';
  $family_name = '';
  $first_name = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';
  $mail_address = '';
  $member_id = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //セッション情報の確認
    if(!isset($_SESSION['admin_id']))
    {
      echo 'nologin';
    }
    else
    {
      //formデータの取得
      if(isset($_POST['order_id']))
      {
        $order_id = htmlspecialchars($_POST['order_id'], ENT_QUOTES);
      }
      if(isset($_POST['status']))
      {
        $status = htmlspecialchars($_POST['status'], ENT_QUOTES);
      }
    }

    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($order_id == '')
    {
      $param .= 'NULL, ';
    }
    else
    {
      $param .= '\''.$order_id.'\',';
    }

    if($status == '')
    {
      $param .= 'NULL';
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
      // 注文情報をDBより呼び出す
      
      $order_param = '';
      if($order_id == '')
      {
        $order_param .= 'NULL';
      }
      else
      {
        $order_param .= '\''.$order_id.'\'';
      }
    
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分
      $seleord = $JointPrc->callProc('proc_SelectOrder',$order_param);
      $ret = $JointPrc->callProc('proc_UpdateOrderStatus',$param); 
      // $member_id = htmlspecialchars($seleord[0]['member_id'], ENT_QUOTES);
      // パラメータの挿入
      // $member_param = '';
      // if($member_id == '')
      // {
      //   $member_param .= 'NULL';
      // }
      // else
      // {
      //   $member_param .= '\''.$member_id.'\'';
      // }
      // $memb = $JointPrc->callProc('proc_SelectMember',$member_param);
      echo json_encode($ret);
      
      if($status == '4'){
        // メール送信部分
        
        $family_name = htmlspecialchars($seleord[0]['family_name'], ENT_QUOTES);
        $first_name = htmlspecialchars($seleord[0]['first_name'], ENT_QUOTES);
        // $postal_code = htmlspecialchars($seleord[0]['postal_code'], ENT_QUOTES);
        // $address = htmlspecialchars($seleord[0]['address'], ENT_QUOTES);
        $telnumber = htmlspecialchars($seleord[0]['telnumber'], ENT_QUOTES);
        $mail_address = htmlspecialchars($seleord[0]['mail_address'], ENT_QUOTES);
        $order_number = htmlspecialchars($seleord[0]['order_number'], ENT_QUOTES);
        $delivery_date = htmlspecialchars($seleord[0]['insert_datetime'], ENT_QUOTES);
        $delivery_family_name = htmlspecialchars($seleord[0]['delivery_family_name'], ENT_QUOTES);
        $delivery_first_name = htmlspecialchars($seleord[0]['delivery_first_name'], ENT_QUOTES);
        $delivery_postal_code = htmlspecialchars($seleord[0]['delivery_postal_code'], ENT_QUOTES);
        $delivery_address = htmlspecialchars($seleord[0]['delivery_address'], ENT_QUOTES);
        $delivery_telnumber = htmlspecialchars($seleord[0]['delivery_telnumber'], ENT_QUOTES);
        $item_name = htmlspecialchars($seleord[0]['item_name'], ENT_QUOTES);
        $quantity = htmlspecialchars($seleord[0]['quantity'], ENT_QUOTES);

        // 発送完了メール
        //題名
        $title = 'ご注文の商品を発送いたしました。【 一括変更（社名）オンラインショップ 】'; //変更必要

        //本文
        $mailbody  = '';
        $mailbody .= $family_name.$first_name.'様<br/>';
        $mailbody .= 'この度は当店でのご注文、誠にありがとうございます。<br/>';
        $mailbody .= '今回のご注文いただいた商品を発送いたしました。<br/>';
        $mailbody .= '内容は以下の通りです。<br/>';
        $mailbody .= 'ご確認の程よろしくお願いいたします。<br/><br/>';
        $mailbody .= '何かご不明な点等ございましたら、お気軽にお問い合わせください。<br/>';
        $mailbody .= 'どうぞよろしくお願いいたします。<br/><br/>';
        // $mailbody .= '【配送業者】<br/>';
        // $mailbody .= '【追跡番号】<br/><br/>';
        // $mailbody .= '下記のURLよりにてお荷物の状況がご確認いただけます。<br/>';
        // $mailbody .= '※配送業者によっては、お荷物の状況の反映までお時間がかかる場合があります。<br/>';
        // $mailbody .= 'お荷物の状況が反映されていない場合は、しばらく時間をおいてご確認ください。<br/><br/>';
        // $mailbody .= 'ヤマト運輸株式会社<br/>';
        // $mailbody .= '<a href="https://toi.kuronekoyamato.co.jp/cgi-bin/tneko">https://toi.kuronekoyamato.co.jp/cgi-bin/tneko</a><br/><br/>';
        // $mailbody .= '日本郵便株式会社<br/>';
        // $mailbody .= '<a href="https://trackings.post.japanpost.jp/services/srv/search/input">https://trackings.post.japanpost.jp/services/srv/search/input</a><br/><br/>';
        $mailbody .= '----------------------------------------------------------<br/>';
        $mailbody .= '【注文番号】'.$order_number.'<br/>';
        $mailbody .= '【注文日時】'.$delivery_date.'<br/>';
        $mailbody .= '----------------------------------------------------------<br/><br/>';
        $mailbody .= '【お届け先】';
        $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
        $mailbody .= '【住　所】〒'. $delivery_postal_code.'　'.$delivery_address.'<br/>';
        // $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/>';
        $mailbody .= '【商　品】'.$item_name.'<br/>';
        $mailbody .= '【数　量】'.$quantity.'<br/>';
        $mailbody .= '----------------------------------------------------------<br/><br/>';
        $mailbody .= '引き続きどうぞよろしくお願いいたします。<br/><br/><br/>';
        $mailbody .= '一括変更（社名）オンラインショップ<br/><br/>'; //変更必要
        $mailbody .= 'お問い合わせ先：<a href="'.HOMEURL.'contact'.'">'.HOMEURL.'contact'.'</a>';

        //宛先作成
        $to_array = array();
        $to_array[] = array('mail_address'=>$mail_address, 'family_name'=>ADMINNAME);
        //メール送信
        echo(mail_send($to_array, $title, $mailbody));

        // 管理者・担当者へのメール
        //題名
        $title = '商品を発送しました。【 一括変更（社名）オンラインショップ 】'; //変更必要

        //本文
        $mailbody  = '';
        $mailbody .= $family_name.$first_name.'様よりご注文いただいた商品を発送しました。<br/><br/>';
        $mailbody .= '管理画面より、ログインしてご確認ください。<br/>';
        $mailbody .= '<a href="'.HOMEURL.'admin/login'.'">'.HOMEURL.'admin/login'.'</a>'.'<br/><br/>';
        $mailbody .= '注文内容：<br/>';
        $mailbody .= '【注文番号】'.$order_number.'<br/>';
        $mailbody .= '【注文日時】'.$delivery_date.'<br/>';
        $mailbody .= '【注文者】'.$family_name.$first_name.'様<br/>';
        $mailbody .= '【電話番号】'.$telnumber.'<br/>';
        $mailbody .= '----------------------------------------------------------<br/>';
        $mailbody .= '【お届け先】';
        $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
        $mailbody .= '【住　所】〒'. $delivery_postal_code.'　'.$delivery_address.'<br/>';
        // $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/>';
        $mailbody .= '【商　品】'.$item_name.'<br/>';
        $mailbody .= '【数　量】'.$quantity.'<br/>';
        $mailbody .= '----------------------------------------------------------<br/><br/>';
        $mailbody .= '一括変更（社名）オンラインショップ<br/>'; //変更必要

        //宛先作成
        $to_array = array();
        $to_array[] = array('mail_address'=>ADMINMAILADDRESS, 'family_name'=>ADMINNAME);
        //メール送信
        echo(mail_send($to_array, $title, $mailbody));
      }
    }
  }
?>