<?php
  require './common/common.php';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'checkkey')
  {
    //セッションにログイン情報が入っているかチェック
    if(!isset($_SESSION['member_id']))
    {
      echo 'nologin';
    }
    else
    {
      $member_id = $_SESSION['member_id'];
      //引数（パラメータ）の作成
      $param = '';
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
        $ret = $JointPrc->callProc('proc_SelectMember',$param);
        $ret_array = array();
        $ret_array['login_member'] = $ret;

        if(!empty($ret))
        {
          //お気に入り情報の取得
          //引数（パラメータ）の作成
          $param = '';
          $param .= '\''.$member_id.'\',';
          $param .= '\'id DESC\',';
          $param .= '-1,';
          $param .= '0';
          $favorite_ret = $JointPrc->callProc('proc_SelectFavoriteList',$param);
          $ret_array['favorite'] = $favorite_ret;

          //買い物かご情報の取得
          //引数（パラメータ）の作成
          $param = '';
          $param .= '\''.$member_id.'\'';
          $cart_ret = $JointPrc->callProc('proc_SelectCartList',$param);
          $ret_array['cart'] = $cart_ret;

          echo json_encode($ret_array);
        }
        else
        {
          echo 'error';
        }
      }
    }
  }
?>