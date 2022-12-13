<?php
  require './common/common.php';

  $mail_address = '';
  $password = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'loginkey')
  {
    //formデータの取得
    if(isset($_POST['mail_address']))
    {
      $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
    }
    if(isset($_POST['password']))
    {
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    }

    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($mail_address == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$mail_address.'\',';
    }
    if($password == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$password.'\',';
    }

    $param .= '\''.ENCKEY.'\'';

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_LoginMember',$param);
      $ret_array = array();
      $ret_array['login_member'] = $ret;
      if(!empty($ret))
      {
        //セッションにログイン情報を挿入
        $_SESSION['member_id'] = $ret[0]['id'];

        //お気に入り情報の取得
        $member_id = $ret[0]['id'];
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
?>