<?php
  require './common/common.php';
  $member_id = '';
  $name = '';
  $limit = -1;
  $offset = 0;

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    if(isset($_SESSION['member_id']))
    {
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);
    }
    //ポストされたデータの取得、代入
    if(isset($_POST['name']))
    {
      $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    }
    if(isset($_POST['limit']))
    {
      $limit = htmlspecialchars($_POST['limit'], ENT_QUOTES);
    }
    if(isset($_POST['offset']))
    {
      $offset = htmlspecialchars($_POST['offset'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($member_id == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$member_id.'\',';
    }

    if($name == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$name.'\',';
    }

    if($limit == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$limit.'\',';
    }

    if($offset == '')
    {
      $param .= 'NULL';
    }
    else
    {
      $param .= '\''.$offset.'\'';
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
      $ret = $JointPrc->callProc('proc_SelectDeliveryAddressList', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>