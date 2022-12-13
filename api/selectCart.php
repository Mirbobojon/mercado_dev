<?php
  require './common/common.php';

  $member_id = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ログイン状態のチェック
    if(!isset($_SESSION['member_id'])){
      echo 'nologin';
    }
    else
    {
      //ログイン会員IDの取得
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);

      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $send_data = [];

        //カートリストの取得
        $in3 = gen_proc_param([$member_id]);
        $res3 = $JointPrc->callProc('proc_SelectCartList', $in3);
        $send_data['cart_list'] = $res3;
        echo json_encode($send_data);
      }
    }
  }
?>