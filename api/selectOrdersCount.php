<?php
  require './common/common.php';
  $status = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['status']))
    {
      $status = htmlspecialchars($_POST['status'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($status == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$status.'\'';
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
      $ret = $JointPrc->callProc('proc_SelectOrdersCount',$param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>