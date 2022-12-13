<?php
  require './common/common.php';
  $order_id = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    if(!isset($_SESSION['admin_id']))
    {
      echo 'nologin';
    }
    else
    {
      //ポストされたデータの取得、代入
      if(isset($_POST['order_id']))
      {
        $order_id = htmlspecialchars($_POST['order_id'], ENT_QUOTES);
      }

      //プロシージャに渡す引数（パラメータ）の作成
      $param = '';

      if($order_id == '')
      {
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$order_id.'\'';
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
        $ret = $JointPrc->callProc('proc_SelectOrder',$param);

        //結果を返す
        echo json_encode($ret);
      }
    }



  }


?>