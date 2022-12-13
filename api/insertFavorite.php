<?php
  require './common/common.php';

  $member_id = '';
  $item_id = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    //ログイン状態のチェック
    if(!isset($_SESSION['member_id'])){
      echo 'nologin';
    }
    else
    {
      //ログイン会員IDの取得
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);

      //postデータの取得
      if(isset($_POST['item_id']))
      {
        $item_id = htmlspecialchars($_POST['item_id'], ENT_QUOTES);
      }

      //お気に入り情報の挿入
      //引数（パラメータ）の作成
      $param = '';

      //パラメータの挿入
      if($member_id == '')
      {
        $param .= 'NULL,';
      }
      else
      {
        $param .= '\''.$member_id.'\',';
      }

      if($item_id == '')
      {
        $param .= 'NULL';
      }
      else
      {
        $param .= '\''.$item_id.'\'';
      }
      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $ret = $JointPrc->callProc('proc_InsertFavorite',$param);

        //お気に入りリストの取得
        //引数（パラメータ）の作成
        $param = '';
        $param .= '\''.$member_id.'\',';
        $param .= '\'id DESC\',';
        $param .= '-1,';
        $param .= '0';
        $favorite_ret = $JointPrc->callProc('proc_SelectFavoriteList',$param);
        echo json_encode($favorite_ret);
      }

    }


  }
?>