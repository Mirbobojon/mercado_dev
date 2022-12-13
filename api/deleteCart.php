<?php
  require './common/common.php';

  $member_id = '';
  $cart_id = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey')
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
      if(isset($_POST['cart_id']))
      {
        $cart_id = htmlspecialchars($_POST['cart_id'], ENT_QUOTES);
      }

      //カート情報の削除
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

      if($cart_id == '')
      {
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$cart_id.'\'';
      }
      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $ret = $JointPrc->callProc('proc_DeleteCart',$param);

        //カートリストの取得
        //引数（パラメータ）の作成
        $param = '';
        $param .= '\''.$member_id.'\'';
        $favorite_ret = $JointPrc->callProc('proc_SelectCartList',$param);
        echo json_encode($favorite_ret);
      }

    }


  }
?>