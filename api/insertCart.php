<?php
  require './common/common.php';

  $member_id = '';
  $item_id = '';
  $quantity = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //ログイン状態のチェック
    if(!isset($_SESSION['member_id'])){
      echo 'nologin';
    }
    else
    {
      //ログイン会員IDの取得
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);

      //postデータの取得
      if(isset($_POST['item_id']))
      {
        $item_id = htmlspecialchars($_POST['item_id'], ENT_QUOTES);
      }
      if(isset($_POST['quantity']))
      {
        $quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
      }

      //お気に入り情報の挿入
      //引数（パラメータ）の作成
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
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$quantity.'\'';
      }

      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $send_data = [];

        // 商品がカートに追加済みでないか確認
        $in1 = gen_proc_param([$member_id]);
        $res1 = $JointPrc->callProc('proc_SelectCartListOnlyItemId', $in1);
        foreach($res1 as $key1 => $data1) {
          if($data1['item_id'] === $item_id) {
            $send_data['result'] = false;
            $send_data['message'] = "already_added";
            echo json_encode($send_data);
            exit();
          }
        }

        // 商品をカートに追加
        $ret = $JointPrc->callProc('proc_InsertCart',$param);

        //カートリストの取得
        $in3 = gen_proc_param([$member_id]);
        $res3 = $JointPrc->callProc('proc_SelectCartList', $in3);
        $send_data['result'] = true;
        $send_data['cart_list'] = $res3;
        echo json_encode($send_data);
      }
    }
  }
?>