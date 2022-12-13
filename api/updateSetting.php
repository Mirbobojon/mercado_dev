<?php
  require './common/common.php';

  $tax_1 = '';
  $tax_2 = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_POST['tax_1']))
    {
      $tax_1 = htmlspecialchars($_POST['tax_1'], ENT_QUOTES);
      //引数（パラメータ）の作成
      $param = '';

      //パラメータの挿入
      $param .= '\'1\', ';

      if($tax_1 == '')
      {
        $param .= '\'\'';
      }
      else
      {
        $param .= '\''.$tax_1.'\'';
      }
      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $ret = $JointPrc->callProc('proc_UpdateTax',$param);
        echo json_encode($param);
      }
    }

    if(isset($_POST['tax_2']))
    {
      $tax_2 = htmlspecialchars($_POST['tax_2'], ENT_QUOTES);
      //引数（パラメータ）の作成
      $param = '';

      //パラメータの挿入
      $param .= '\'2\', ';

      if($tax_2 == '')
      {
        $param .= '\'\'';
      }
      else
      {
        $param .= '\''.$tax_2.'\'';
      }
      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $ret = $JointPrc->callProc('proc_UpdateTax',$param);
      }
    }
  }
?>