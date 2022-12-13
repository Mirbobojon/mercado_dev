<?php
  require './common/common.php';
  $id = '';
  $member_id = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey')
  {
    if(!isset($_SESSION['member_id']))
    {
      echo 'nologin';
    }
    else
    {
      $member_id = $_SESSION['member_id'];
      //ポストされたデータの取得、代入
      if(isset($_POST['id']))
      {
        $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
      }

      //プロシージャに渡す引数（パラメータ）の作成
      $param = '';

      if($id == '')
      {
        $param .= 'NULL,';
      }
      else
      {
        $param .= '\''.$id.'\',';
      }

      if($member_id == '')
      {
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$member_id.'\'';
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
        $ret = $JointPrc->callProc('proc_DeleteOrderPostAddress', $param);

        //結果を返す
        echo json_encode($param);
      }
    }

  }


?>