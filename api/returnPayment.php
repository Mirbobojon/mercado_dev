<?php
  require './mailsend.php';

  //受け取った支払い取引IDのレコードをordersレコードに移動
  $trading_id = '';

  if(isset($_GET['trading_id']))
  {
    $trading_id = htmlspecialchars($_GET['trading_id'], ENT_QUOTES);
  }
  else
  {
    header('Location: '. HOMEURL);
  }
  //パラメータ作成
  $param = '';
  //パラメータの挿入
  if($trading_id == '')
  {
    $param .= 'NULL';
  }
  else
  {
    $param .= '\''.$trading_id.'\'';
  }

  $db = new MySQL(); // DBに接続する
  if(!$db->connect())
  {
      echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
  }
  else
  {
    $JointPrc = new JointPrc (); // JointPrc呼び出し部分
    $ret = $JointPrc->callProc('proc_InsertOrder',$param);
    if(empty($ret))
    {
      header('Location: '. HOMEURL);
        $ret_flag = false;
    }
    else
    {
      $ret_flag = true;
    }
  }
  if($ret_flag)
  {
    //member_idの取得
    $JointPrc = new JointPrc (); // JointPrc呼び出し部分
    $ret = $JointPrc->callProc('proc_SelectMemberId',$param);

    if(empty($ret))
    {
      header('Location: '. HOMEURL);
    }
    else{
      $member_id = $ret[0]['member_id'];

      $member_param = '';
      if($member_id == '')
      {
        $member_param .= 'NULL';
      }
      else
      {
        $member_param .= '\''.$member_id.'\'';
      }

      $JointPrc = new JointPrc (); // JointPrc呼び出し部分
      $stmt = $JointPrc->callProc('proc_SelectMember',$member_param);

      $delivery_date = date("Y年m月d日H:i");

      $all_total_price = 0;

      //商品情報の取得
      $orders = $JointPrc->callProc('proc_SelectOrderInfo',$param);

      if(is_array($orders))
      {
        $count = count($orders);
        //合計金額の計算
        for($i=0;$i<$count;$i++)
        {
          $total_price = ceil(($orders[$i]['price']*(100+($orders[$i]['tax_value'])))/100)*$orders[$i]['quantity']+$orders[$i]['postage'];
          //総合計の計算
          $all_total_price += $total_price;
        }
        // メール送信部分
        $family_name = htmlspecialchars($stmt[0]['family_name'], ENT_QUOTES);
        $first_name = htmlspecialchars($stmt[0]['first_name'], ENT_QUOTES);
        $order_number = htmlspecialchars($orders[0]['order_number'], ENT_QUOTES);
        $postal_code = htmlspecialchars($stmt[0]['postal_code'], ENT_QUOTES);
        $address = htmlspecialchars($stmt[0]['address'], ENT_QUOTES);
        $telnumber = htmlspecialchars($stmt[0]['telnumber'], ENT_QUOTES);
        $mail_address = htmlspecialchars($stmt[0]['mail_address'], ENT_QUOTES);
        $pay_type_name = htmlspecialchars($orders[0]['pay_type_name'], ENT_QUOTES);

        //注文完了のメール
        //題名
        $title = 'ご注文いただきありがとうございます。【 一括変更（社名）オンラインショップ 】'; //変更必要

        //本文
        $mailbody  = '';
        $mailbody .= $family_name.$first_name.'様<br/><br/>';
        $mailbody .= 'この度は当店でのご注文、誠にありがとうございます。<br/>';
        $mailbody .= '今回のご注文内容は以下の通りです。<br/>';
        $mailbody .= 'ご確認の程よろしくお願いいたします。<br/>';
        $mailbody .= 'ご注文いただいた商品につきましては、ご入金(ご注文)確認後、順次発送の手配をさせていただきます。<br/><br/>';
        $mailbody .= '何かご不明な点等ございましたら、お気軽にお問い合わせください。<br/>';
        $mailbody .= '【注文番号】'.$order_number.'<br/>';
        $mailbody .= '【注文日時】'.$delivery_date.'<br/>';
        $mailbody .= '【注文者】'.$family_name.$first_name.'様<br/>';
        $mailbody .= '【住　所】〒'.$postal_code.'　'.$address.'</br>';
        $mailbody .= '【電話番号】'.$telnumber.'<br/>';
        $mailbody .= '【支払方法】'.$pay_type_name.'<br/>';
        $mailbody .= '【合計金額】'.number_format($all_total_price).'円<br/><br/>';
        $mailbody .= '----------------------------------------------------------<br/>';

        for($i=0;$i<$count;$i++)
        {
          if($i===0)
          {
            //お届け先情報の格納
            if(isset($orders[$i]['delivery_family_name']))
            {
              $delivery_family_name = htmlspecialchars($orders[$i]['delivery_family_name'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_first_name']))
            {
              $delivery_first_name = htmlspecialchars($orders[$i]['delivery_first_name'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_postal_code']))
            {
              $delivery_postal_code = htmlspecialchars($orders[$i]['delivery_postal_code'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_address']))
            {
              $delivery_address = htmlspecialchars($orders[$i]['delivery_address'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_telnumber']))
            {
              $delivery_telnumber = htmlspecialchars($orders[$i]['delivery_telnumber'], ENT_QUOTES);
            }
            $mailbody .= '【お届け先】<br/>';
            $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
            $mailbody .= '【住　所】〒'.$delivery_postal_code.'<br/>'.$delivery_address.'</br>';
            $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/>';
            for($k=0;$k<$count;$k++){
              if($delivery_family_name === $orders[$k]['delivery_family_name'])
                {
                $mailbody .= '【商　品】<br/>';
                $mailbody .= $orders[$k]['item_name'].'　'.$orders[$k]['standard'].'<br/>';
                $mailbody .= '【数　量】'.$orders[$k]['quantity'].'<br/><br/>';
              }
            }

          }
          else{
            $pre = $i - 1;

            if($orders[$i]['delivery_family_name'] !== $orders[$pre]['delivery_family_name'])
            {
              //お届け先情報の格納
              if(isset($orders[$i]['delivery_family_name']))
              {
                $delivery_family_name = htmlspecialchars($orders[$i]['delivery_family_name'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_first_name']))
              {
                $delivery_first_name = htmlspecialchars($orders[$i]['delivery_first_name'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_postal_code']))
              {
                $delivery_postal_code = htmlspecialchars($orders[$i]['delivery_postal_code'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_address']))
              {
                $delivery_address = htmlspecialchars($orders[$i]['delivery_address'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_telnumber']))
              {
                $delivery_telnumber = htmlspecialchars($orders[$i]['delivery_telnumber'], ENT_QUOTES);
              }
              $mailbody .= '【お届け先】<br/>';
              $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
              $mailbody .= '【住　所】〒'.$delivery_postal_code.'<br/>'.$delivery_address.'</br>';
              $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/><br/>';
              for($k=0;$k<$count;$k++){

              if($delivery_family_name === $orders[$k]['delivery_family_name'])
                {
                  $mailbody .= '【商　品】<br/>';
                  $mailbody .= $orders[$k]['item_name'].'　'.$orders[$k]['standard'].'<br/>';
                  $mailbody .= '【数　量】'.$orders[$k]['quantity'].'<br/><br/>';
                }
              }
            }
          }
        }
        $mailbody .= '----------------------------------------------------------<br/><br/>';
        $mailbody .= '通常、ご注文（入金済み）から5営業日以内に発送しておりますが、発送までお時間がかかる場合もあります。<br/>';
        $mailbody .= '詳しくは「<a href="'.HOMEURL.'howto'.'">'.'ご利用について'.'</a>'.'」をご参照ください。<br/><br/>';
        $mailbody .= '引き続きどうぞよろしくお願いいたします。<br/><br/><br/>';
        $mailbody .= '一括変更（社名）オンラインショップ<br/><br/>'; //変更必要
        $mailbody .= 'お問い合わせ先：<a href="'.HOMEURL.'contact'.'">'.HOMEURL.'contact'.'</a>';

        //宛先作成
        $to_array = array();
        $to_array[] = array('mail_address'=>$mail_address, 'family_name'=>ADMINNAME);

        // //メール送信
        mail_send($to_array, $title, $mailbody);

        // 管理者・担当者へのメール
        //題名
        $title = 'オンラインショップより注文がありました【 一括変更（社名）オンラインショップ 】'; //変更必要
    
        //本文
        $mailbody  = '';
        $mailbody .= $family_name.$first_name.'様よりご注文がありました。<br/><br/>';
        $mailbody .= '管理画面より、ログインしてご確認ください。<br/>';
        $mailbody .= '<a href="'.HOMEURL.'admin/login'.'">'.HOMEURL.'admin/login'.'</a>'.'<br/><br/>';
        $mailbody .= '注文内容<br/>';
        $mailbody .= '【注文番号】'.$order_number.'<br/>';
        $mailbody .= '【注文日時】'.$delivery_date.'<br/>';
        $mailbody .= '【注文者】'.$family_name.$first_name.'様<br/>';
        $mailbody .= '【住　所】〒'.$postal_code.'　'.$address.'</br>';
        $mailbody .= '【電話番号】'.$telnumber.'<br/>';
        $mailbody .= '【支払方法】'.$pay_type_name.'<br/>';
        $mailbody .= '【合計金額】'.number_format($all_total_price).'円<br/><br/>';
        $mailbody .= '----------------------------------------------------------<br/>';

        for($i=0;$i<$count;$i++)
        {
          if($i===0)
          {
            //お届け先情報の格納
            if(isset($orders[$i]['delivery_family_name']))
            {
              $delivery_family_name = htmlspecialchars($orders[$i]['delivery_family_name'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_first_name']))
            {
              $delivery_first_name = htmlspecialchars($orders[$i]['delivery_first_name'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_postal_code']))
            {
              $delivery_postal_code = htmlspecialchars($orders[$i]['delivery_postal_code'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_address']))
            {
              $delivery_address = htmlspecialchars($orders[$i]['delivery_address'], ENT_QUOTES);
            }
            if(isset($orders[$i]['delivery_telnumber']))
            {
              $delivery_telnumber = htmlspecialchars($orders[$i]['delivery_telnumber'], ENT_QUOTES);
            }
            $mailbody .= '【お届け先】<br/>';
            $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
            $mailbody .= '【住　所】〒'.$delivery_postal_code.'<br/>'.$delivery_address.'</br>';
            $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/>';
            for($k=0;$k<$count;$k++){
              if($delivery_family_name === $orders[$k]['delivery_family_name'])
                {
                $mailbody .= '【商　品】<br/>';
                $mailbody .= $orders[$k]['item_name'].'　'.$orders[$k]['standard'].'<br/>';
                $mailbody .= '【数　量】'.$orders[$k]['quantity'].'<br/><br/>';
              }
            }

          }
          else{
            $pre = $i - 1;

            if($orders[$i]['delivery_family_name'] !== $orders[$pre]['delivery_family_name'])
            {
              //お届け先情報の格納
              if(isset($orders[$i]['delivery_family_name']))
              {
                $delivery_family_name = htmlspecialchars($orders[$i]['delivery_family_name'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_first_name']))
              {
                $delivery_first_name = htmlspecialchars($orders[$i]['delivery_first_name'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_postal_code']))
              {
                $delivery_postal_code = htmlspecialchars($orders[$i]['delivery_postal_code'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_address']))
              {
                $delivery_address = htmlspecialchars($orders[$i]['delivery_address'], ENT_QUOTES);
              }
              if(isset($orders[$i]['delivery_telnumber']))
              {
                $delivery_telnumber = htmlspecialchars($orders[$i]['delivery_telnumber'], ENT_QUOTES);
              }
              $mailbody .= '【お届け先】<br/>';
              $mailbody .= $delivery_family_name.$delivery_first_name.'様<br/>';
              $mailbody .= '【住　所】〒'.$delivery_postal_code.'<br/>'.$delivery_address.'</br>';
              $mailbody .= '【電話番号】'.$delivery_telnumber.'<br/><br/>';
              for($k=0;$k<$count;$k++){

              if($delivery_family_name === $orders[$k]['delivery_family_name'])
                {
                  $mailbody .= '【商　品】<br/>';
                  $mailbody .= $orders[$k]['item_name'].'　'.$orders[$k]['standard'].'<br/>';
                  $mailbody .= '【数　量】'.$orders[$k]['quantity'].'<br/><br/>';
                }
              }
            }
          }
        }
        $mailbody .= '----------------------------------------------------------<br/><br/>';
        $mailbody .= '一括変更（社名）オンラインショップ'; //変更必要
  
        //宛先作成
        $to_array = array();
        $to_array[] = array('mail_address'=>ADMINMAILADDRESS, 'family_name'=>ADMINNAME);
        //メール送信
        mail_send($to_array, $title, $mailbody);


      }

      //買い物カゴ情報の削除
      $param = '';
      if($member_id == '')
      {
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$member_id.'\'';
      }
      $ret = $JointPrc->callProc('proc_DeleteCartAfterOrderInsert',$param);

      //お届け先の削除
      $ret = $JointPrc->callProc('proc_DeleteOrderPostAddressAfterOrderInser',$param);

      header('Location: ' . HOMEURL . 'mypage/order_completed/' . $trading_id);
      // echo 'true';
    }
  }
?>