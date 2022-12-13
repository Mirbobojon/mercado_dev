<?php
require './common/common.php';

try {
  // throw new Exception("例外テスト");
  // write_log("後続処理中断確認");

  $data = $_POST['data'];
  $values = json_decode($data);
  // write_log($values);
  $total_price = '';
  $member_id = '';
  $location_id = '';
  $nonce = '';
  $verificationToken = '';
  $bearer_token = BEARERTOKEN;
  $request_url = REQUESTURL;
  $procedure = '';
  $items = '';
  $delivery_family_name = '';
  $delivery_first_name = '';
  $delivery_family_name_furigana = '';
  $delivery_first_name_furigana = '';
  $delivery_postal_code = '';
  $delivery_address = '';
  $delivery_telnumber = '';

  $send_data = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($values->total_price)) {
      $total_price = $values->total_price;
    }
    if (isset($values->member_id)) {
      $member_id = $values->member_id;
    }
    if (isset($values->location_id)) {
      $location_id = $values->location_id;
    }
    if (isset($values->nonce)) {
      $nonce = $values->nonce;
    }
    if (isset($values->verificationToken)) {
      $verificationToken = $values->verificationToken;
    }
    if (isset($values->procedure)) {
      $procedure = $values->procedure;
    }
    if (isset($values->items)) {
      $items = $values->items;
    }
    if (isset($values->delivery_family_name)) {
      $delivery_family_name = $values->delivery_family_name;
    }
    if (isset($values->delivery_first_name)) {
      $delivery_first_name = $values->delivery_first_name;
    }
    if (isset($values->delivery_family_name_furigana)) {
      $delivery_family_name_furigana = $values->delivery_family_name_furigana;
    }
    if (isset($values->delivery_first_name_furigana)) {
      $delivery_first_name_furigana = $values->delivery_first_name_furigana;
    }
    if (isset($values->delivery_postal_code)) {
      $delivery_postal_code = $values->delivery_postal_code;
    }
    if (isset($values->delivery_address)) {
      $delivery_address = $values->delivery_address;
    }
    if (isset($values->delivery_telnumber)) {
      $delivery_telnumber = $values->delivery_telnumber;
    }


    // Square決済処理　ここから

    //送信されたロケーションidと比べる
    if ($location_id == LOCATIONID) {
      $params = (object)[
        // Squareカード決済エラー処理テスト用　ここから
        // ※3Dセキュア導入後は以下source_idではBad Requestでテストできなくなった
        // 参考：https://developer.squareup.com/docs/devtools/sandbox/payments
        // 成功用
        // "source_id" => 'cnon:card-nonce-ok',
        // "source_id" => 'ccof:customer-card-id-ok',
        // "source_id" => 'cnon:gift-card-nonce-ok',
        // "source_id" => 'ccof:customer-card-id-requires-verification',

        // エラー用
        // "source_id" => 'cnon:card-nonce-rejected-cvv',
        // "source_id" => 'cnon:card-nonce-rejected-postalcode',
        // "source_id" => 'ccof:customer-card-id-rejected-postalcode',
        // "source_id" => 'cnon:card-nonce-rejected-expiration',
        // "source_id" => 'ccof:customer-card-id-rejected-expiration',
        // "source_id" => 'cnon:card-nonce-declined',
        // "source_id" => 'ccof:customer-card-id-declined',
        // "source_id" => 'cnon:card-nonce-already-used',
        // "source_id" => 'cnon:gift-card-nonce-insufficient-funds',
        // "source_id" => 'cnon:gift-card-nonce-insufficient-permission',

        // Squareカード決済エラー処理テスト用　ここまで


        "source_id" => $nonce,
        "idempotency_key" => uniqid(),
        "amount_money" => (object)[
          "amount" => $total_price,
          "currency" => "JPY"
        ],
        "location_id" => LOCATIONID,
        "autocomplete" => true,
      ];

      // 3Dセキュア用トークンがある場合はパラメータに追加
      if ($verificationToken !== '') {
        $params->verification_token = $verificationToken;
      }
      write_log($params);

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $bearer_token));
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');  // メソッド
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // 証明書の検証を行わない
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
      curl_setopt($curl, CURLOPT_TIMEOUT, 5);  // タイムアウトの秒数
      curl_setopt($curl, CURLOPT_URL, $request_url);
      $result = curl_exec($curl);
      curl_close($curl);
      $json = json_decode($result);
      write_log($json);

      // Square決済処理　ここまで

      if (isset($json->errors)) {
        // エラーが出力された場合
        $err_codes = [];
        foreach ($json->errors as $key => $err) {
          array_push($err_codes, $err->code);
        }
        $send_data['result'] = false;
        $send_data['errors'] = $err_codes;
        echo json_encode($send_data);
      } else {
        if ($json->payment->status == "COMPLETED") {
          //注文情報の状況をDBに登録
          //支払取引IDを生成
          //日本時間に合わせる
          date_default_timezone_set('Asia/Tokyo');

          //ランダムな文字列の生成
          $str = 'abcdefghijklmnopqrstuvwxyz';
          $rand_str = substr(str_shuffle($str), 0, 3);

          $trading_id = $rand_str . date('ymdHis');


          //itemsの数だけ繰り返す
          if (is_array($items)) {
            $items_count = count($items);

            $insert_flag = false;

            for ($i = 0; $i < $items_count; $i++) {
              //引数（パラメータ）の作成
              $param = '';
              //パラメータの挿入
              if ($member_id == "") {
                $param .= 'NULL,';
              } else {
                $param .= '\'' . $member_id . '\',';
              }

              if ($trading_id == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $trading_id . '\',';
              }

              if ($items[$i]->item_id == "") {
                $param .= 'NULL,';
              } else {
                $param .= '\'' . $items[$i]->item_id . '\',';
              }

              if ($items[$i]->quantity == "") {
                $param .= 'NULL,';
              } else {
                $param .= '\'' . $items[$i]->quantity . '\',';
              }

              if ($delivery_family_name == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_family_name . '\',';
              }

              if ($delivery_first_name == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_first_name . '\',';
              }

              if ($delivery_family_name_furigana == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_family_name_furigana . '\',';
              }

              if ($delivery_first_name_furigana == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_first_name_furigana . '\',';
              }

              if ($delivery_postal_code == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_postal_code . '\',';
              }

              if ($delivery_address == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_address . '\',';
              }

              if ($delivery_telnumber == "") {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $delivery_telnumber . '\',';
              }
              $param .= '\'1\',';  //納品書フラグ
              $param .= '\'2\',';  //支払方法:クレジット決済

              if ($total_price == "")  //決済金額
              {
                $param .= 'NULL,';
              } else {
                $param .= '\'' . $total_price . '\',';
              }

              if ($trading_id == "")  //注文番号
              {
                $param .= '\'\',';
              } else {
                $param .= '\'' . $trading_id . '\',';
              }
              $param .= '\'2\'';  //注文状態:支払済み

              $db = new MySQL(); // DBに接続する
              if (!$db->connect()) {
                $send_data['result'] = false;
                $send_data['details'] = 'database_connect_error';
                echo json_encode($send_data);
                exit();
              } else {
                $JointPrc = new JointPrc(); // JointPrc呼び出し部分

                $ret = $JointPrc->callProc('proc_InsertOrders', $param);
                if ($ret) {
                  $insert_flag = true;

                  // 商品の在庫数を減らす
                  $in4 = gen_proc_param([
                    $items[$i]->item_id,
                    $items[$i]->quantity
                  ]);
                  $res4 = $JointPrc->callProc('proc_UpdateItemStockQuantity', $in4);
                } else {
                  $insert_flag = false;
                }
              }
            }

            if ($insert_flag == true) {
              //注文完了メールを送信する
              //member_idからメールアドレスを取得
              $param = '';
              //パラメータの挿入
              if ($member_id == "") {
                $param .= 'NULL';
              } else {
                $param .= '\'' . $member_id . '\'';
              }

              $member_mailaddress = $JointPrc->callProc('proc_SelectMemberMailaddressForMemberId
            ', $param);

              //言語と文字コードの使用宣言
              mb_language("uni");
              mb_internal_encoding("UTF-8");

              $to = $member_mailaddress[0]['mail_address'];  //宛先設定(注文者)

              $subject = "ご注文ありがとうございます。";
              //本文
              $message  = '';
              $message .= '以下の内容で注文を承りました。<br/><br/>';
              $message .= '---------------------------------------------------';
              $message .= '<br/>';
              for ($i = 0; $i < $items_count; $i++) {
                $message .= '商品名：';
                $message .= $items[$i]->name;
                $message .= '<br/>';
                $message .= '数量：';
                $message .= $items[$i]->quantity;
                $message .= '個';
                $message .= '<br/>';
                $message .= '<br/>';
              }
              $message .= '合計金額：';
              $message .= $total_price;
              $message .= '円';
              $message .= '<br/>';
              $message .= '---------------------------------------------------';

              //メールヘッダ設定
              $headers = "From: " . ADMINNAME . "<" . ADMINMAILADDRESS . ">";
              $headers  .= "\r\n";
              $headers  .= "Content-Type: text/html; charset=UTF-8";

              if (mb_send_mail($to, $subject, $message, $headers)) {
                $send_data['result'] = true;
                echo json_encode($send_data);
              } else {
                $send_data['result'] = false;
                $send_data['details'] = 'mail_error';
                echo json_encode($send_data);
              }

              //買い物カゴから情報を削除
              for ($i = 0; $i < $items_count; $i++) {
                $param = '';
                //パラメータの挿入
                if ($member_id == "") {
                  $param .= 'NULL,';
                } else {
                  $param .= '\'' . $member_id . '\',';
                }
                if ($items[$i]->item_id == "") {
                  $param .= 'NULL';
                } else {
                  $param .= '\'' . $items[$i]->item_id . '\'';
                }
                $res = $JointPrc->callProc('proc_DeleteCartAfterOrderCompleted', $param);
              }
            } else {
              $send_data['result'] = false;
              $send_data['details'] = 'procedure_error';
              echo json_encode($send_data);
            }
          } else {
            $send_data['result'] = false;
            $send_data['details'] = 'item_error';
            echo json_encode($send_data);
          }
        } else {
          $send_data['result'] = false;
          $send_data['details'] = 'square_unknown_error';
          echo json_encode($send_data);
        }
      }
    } else {
      $send_data['result'] = false;
      $send_data['details'] = 'square_location_error';
      echo json_encode($send_data);
    }
  } else {
    $send_data['result'] = false;
    $send_data['details'] = 'request_error';
    echo json_encode($send_data);
  }
} catch (Exception $e) {
  // エラーログ出力
  date_default_timezone_set('Asia/Tokyo');
  write_log(date('Y-m-d H:i:s') . "\n" . $e->getMessage());

  $send_data['result'] = false;
  $send_data['details'] = 'server_program_error';
  echo json_encode($send_data);
}
