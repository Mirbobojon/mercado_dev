<?php
  require './common/common.php';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['address_1'])) {
      $address1 = htmlspecialchars($_POST['address_1'], ENT_QUOTES);
    } else {
      echo '';
      exit();
    }


    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      //呼び出し
      $in1 = gen_proc_param([$address1]);
      $ret = $JointPrc->callProc('proc_SelectAdditionalShippingFee', $in1);

      //結果を返す
      echo json_encode($ret);
    }

  }
?>