<?php
$trading_id = $_POST['trading_id'];
$payment_type = $_POST['payment_type'];
$fix_params = "";
$id = $_POST['id'];
$inform_url = "";
$seq_merchant_id = $_POST['seq_merchant_id'];
$payment_term_day = "";
$payment_term_min = "";
$payment_class = "";
$use_card_conf_number = "";
$customer_id = "";
$threedsecure_ryaku = "";
$hash_key = "GE9JRt5JZ1tVMn";

// create hash hex string
$org_str = $trading_id .
          $payment_type .
          // $fix_params .
          $id .
          // $inform_url .
          $seq_merchant_id .
          // $payment_term_day .
          // $payment_term_min .
          // $payment_class .
          // $use_card_conf_number .
          // $customer_id .
          // $threedsecure_ryaku .
          $hash_key;
$hash_str = hash("sha256", $org_str);


  // create random string
  $rand_str = "";
  $rand_char = array('a','b','c','d','e','f','A','B','C','D','E','F','0','1','2','3','4','5','6','7','8','9');
  for($i=0; ($i<20 && rand(1,10) != 10); $i++){
    $rand_str .= $rand_char[rand(0, count($rand_char)-1)];
  }

  // echo $hash_str.$rand_str;
  echo $hash_str.$rand_str;
  // print $hash_str.$rand_str;

  // $url = 'https://sandbox.paygent.co.jp/v/u/request';
  // $param = array(
  //   'trading_id' => $trading_id,
  //   'payment_type' => $payment_type,
  //   'id' => $id,
  //   'seq_merchant_id' => $seq_merchant_id,
  //   'merchant_name' => $merchant_name,
  //   'payment_detail' => $payment_detail,
  //   'payment_detail_kane' => $payment_detail_kane,
  //   'return_url' => $return_url,
  //   'stop_return_url' => $stop_return_url,
  //   'copy_right' => $copy_right,
  //   'finish_disable' => $finish_disable,
  //   'mail_send_flg_success' => $mail_send_flg_success,
  //   'mail_send_flg_failure' => $mail_send_flg_failure,
  //   'hc' => $hash_str
  // );
  // $ch = curl_init();
  // curl_setopt($ch, CURLOPT_URL, $url);
  // curl_setopt($ch, CURLOPT_POST, true);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // $http_str = curl_exec($ch);
  // curl_close($ch);
?>

