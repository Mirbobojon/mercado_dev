<?php
  require './common/common.php';

  $mailing_list_id = '';
  $title = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_POST['mailing_list_id']))
    {
      $mailing_list_id = htmlspecialchars($_POST['mailing_list_id'], ENT_QUOTES);
    }
    if(isset($_POST['title']))
    {
      $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
    }


    //商品情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($mailing_list_id == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$mailing_list_id.'\',';
    }

    if($title == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$title.'\'';
    }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_UpdateMailingList',$param);
      echo json_encode($ret);
    }
  }
?>