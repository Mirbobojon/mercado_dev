<?php
  // require './common/common.php';
  require './mailsend.php';

  $member_id = '';
  $trading_id = '';
  $item_id = '';
  $quantity = '';
  $delivery_family_name = '';
  $delivery_first_name = '';
  $delivery_family_name_furigana = '';
  $delivery_first_name_furigana = '';
  $delivery_postal_code = '';
  $delivery_address = '';
  $delivery_telnumber = '';
  $delivery_slip_flag = '';
  $myhouse_flag = 0;
  $pay_type = '';
  $price = '';
  $tax_value = '';
  $postage = '';
  $order_number = '';
  $family_name = '';
  $first_name = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';
  $mail_address = '';
  $all_total_price = 0;

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    if(!isset($_SESSION['member_id']))
    {
      echo 'nologin';
    }
    else
    {
      //会員情報の取得
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);

      if(isset($_POST['trading_id']))
      {
        //支払い取引IDの取得
        $trading_id = htmlspecialchars($_POST['trading_id'], ENT_QUOTES);
      }

      //注文番号の作成
      $date = htmlspecialchars(date("YmdHi"), ENT_QUOTES);
      $order_number = 'AED-'.$date;

      //注文情報の格納
      $orders = json_decode($_POST['orders']);
      $ret_flag = false;

      //支払い方法の格納
      $pay_type_value = json_decode($_POST['pay_type']);

      if(is_array($orders))
      {
        $order_count = count($orders);
        for($i=0;$i<$order_count;$i++)
        {
          //お届け先情報の格納
          if(isset($orders[$i]->delivery_family_name))
          {
            $delivery_family_name = htmlspecialchars($orders[$i]->delivery_family_name, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_first_name))
          {
            $delivery_first_name = htmlspecialchars($orders[$i]->delivery_first_name, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_family_name_furigana))
          {
            $delivery_family_name_furigana = htmlspecialchars($orders[$i]->delivery_family_name_furigana, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_first_name_furigana))
          {
            $delivery_first_name_furigana = htmlspecialchars($orders[$i]->delivery_first_name_furigana, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_postal_code))
          {
            $delivery_postal_code = htmlspecialchars($orders[$i]->delivery_postal_code, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_address))
          {
            $delivery_address = htmlspecialchars($orders[$i]->delivery_address, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_telnumber))
          {
            $delivery_telnumber = htmlspecialchars($orders[$i]->delivery_telnumber, ENT_QUOTES);
          }
          if(isset($orders[$i]->delivery_slip_flag))
          {
            $delivery_slip_flag = htmlspecialchars($orders[$i]->delivery_slip_flag, ENT_QUOTES);
          }
          if(isset($orders[$i]->myhouse_flag))
          {
            $myhouse_flag = htmlspecialchars($orders[$i]->myhouse_flag, ENT_QUOTES);
          }
          if(isset($pay_type_value->id))
          {
            $pay_type = htmlspecialchars($pay_type_value->id, ENT_QUOTES);
          }
          if(isset($pay_type_value->name))
          {
            $pay_type_name = htmlspecialchars($pay_type_value->name, ENT_QUOTES);
          }

          //商品情報の格納
          if(is_array($orders[$i]->item_list)&&!empty($orders[$i]->item_list))
          {
            $item_count = count($orders[$i]->item_list);
            for($k=0;$k<$item_count;$k++)
            {
              if(isset($orders[$i]->item_list[$k]->item_id))
              {
                $item_id = htmlspecialchars($orders[$i]->item_list[$k]->item_id, ENT_QUOTES);
              }
              if(isset($orders[$i]->item_list[$k]->number))
              {
                $quantity = htmlspecialchars($orders[$i]->item_list[$k]->number, ENT_QUOTES);
              }
              if(isset($orders[$i]->item_list[$k]->price))
              {
                $price = htmlspecialchars($orders[$i]->item_list[$k]->price, ENT_QUOTES);
              }
              if(isset($orders[$i]->item_list[$k]->tax_value))
              {
                $tax_value = htmlspecialchars($orders[$i]->item_list[$k]->tax_value, ENT_QUOTES);
              }
              if(isset($orders[$i]->item_list[$k]->postage))
              {
                $postage = htmlspecialchars($orders[$i]->item_list[$k]->postage, ENT_QUOTES);
              }

              //合計金額の計算
              $total_price = ceil(($price*(100+($tax_value)))/100)*$quantity+$postage;

              //総合計の計算
              $all_total_price += $total_price;

              //パラメータ作成
              $param = '';
              //パラメータの挿入
              if($member_id == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$member_id.'\',';
              }
              if($trading_id == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$trading_id.'\',';
              }
              if($item_id == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$item_id.'\',';
              }
              if($quantity == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$quantity.'\',';
              }
              if($delivery_family_name == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_family_name.'\',';
              }
              if($delivery_first_name == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_first_name.'\',';
              }
              if($delivery_family_name_furigana == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_family_name_furigana.'\',';
              }
              if($delivery_first_name_furigana == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_first_name_furigana.'\',';
              }
              if($delivery_postal_code == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_postal_code.'\',';
              }
              if($delivery_address == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_address.'\',';
              }
              if($delivery_telnumber == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$delivery_telnumber.'\',';
              }
              if($delivery_slip_flag == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$delivery_slip_flag.'\',';
              }
              if($pay_type == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$pay_type.'\',';
              }
              if($total_price == '')
              {
                $param .= 'NULL,';
              }
              else
              {
                $param .= '\''.$total_price.'\',';
              }
              if($order_number == '')
              {
                $param .= '\'\',';
              }
              else
              {
                $param .= '\''.$order_number.'\',';
              }
              if($myhouse_flag == '')
              {
                $param .= 'NULL';
              }
              else
              {
                $param .= '\''.$myhouse_flag.'\'';
              }

              $db = new MySQL(); // DBに接続する
              if(!$db->connect())
              {
                  echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
              }
              else
              {
                // ログイン会員をDBより呼び出す
                // SQLステートメントを実行し、結果を変数に格納
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
                $ret = $JointPrc->callProc('proc_InsertOrderTemp',$param);
                echo json_encode($ret);
                // if($ret)
                // {
                //   $ret_flag = true;
                // }
                // else
                // {
                //   $ret_flag = false;
                // }
              }
            }
          }
        }
      }
    }
  }

?>