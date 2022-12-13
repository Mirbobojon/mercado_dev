<?php
  require './common/common.php';
  $department_id = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['department_id']))
    {
      $department_id = htmlspecialchars($_POST['department_id'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($department_id == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$department_id.'\'';
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
      $ret = $JointPrc->callProc('proc_SelectDepartment', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>