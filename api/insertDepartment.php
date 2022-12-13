<?php
  require './common/common.php';

  $name = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //formデータの取得
    if(isset($_POST['name']))
    {
      $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    }

    //商品情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($name == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$name.'\'';
    }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_InsertDepartment',$param);
      echo json_encode($ret);
    }
  }
?>