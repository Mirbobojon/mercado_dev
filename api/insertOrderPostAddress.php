<?php
  require './common/common.php';

  $member_id = '';
  $family_name = '';
  $first_name = '';
  $family_name_furigana = '';
  $first_name_furigana = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';
  $delivery_slip_flag = '';
  $myhouse_flag = 0;

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    if(!isset($_SESSION['member_id']))
    {
      echo 'nologin';
    }
    else
    {
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);

      //formデータの取得
      if(isset($_POST['family_name']))
      {
        $family_name = htmlspecialchars($_POST['family_name'], ENT_QUOTES);
      }
      if(isset($_POST['first_name']))
      {
        $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
      }
      if(isset($_POST['family_name_furigana']))
      {
        $family_name_furigana = htmlspecialchars($_POST['family_name_furigana'], ENT_QUOTES);
      }
      if(isset($_POST['first_name_furigana']))
      {
        $first_name_furigana = htmlspecialchars($_POST['first_name_furigana'], ENT_QUOTES);
      }
      if(isset($_POST['postal_code']))
      {
        $postal_code = htmlspecialchars($_POST['postal_code'], ENT_QUOTES);
      }
      if(isset($_POST['address']))
      {
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
      }
      if(isset($_POST['telnumber']))
      {
        $telnumber = htmlspecialchars($_POST['telnumber'], ENT_QUOTES);
      }
      if(isset($_POST['delivery_slip_flag']))
      {
        $delivery_slip_flag = htmlspecialchars($_POST['delivery_slip_flag'], ENT_QUOTES);
      }
      if(isset($_POST['myhouse_flag']))
      {
        $myhouse_flag = htmlspecialchars($_POST['myhouse_flag'], ENT_QUOTES);
      }

      //商品情報の挿入
      //引数（パラメータ）の作成
      $param = '';

      //パラメータの挿入
      if($member_id == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$member_id.'\',';
      }

      if($family_name == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$family_name.'\',';
      }

      if($first_name == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$first_name.'\',';
      }

      if($family_name_furigana == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$family_name_furigana.'\',';
      }

      if($first_name_furigana == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$first_name_furigana.'\',';
      }

      if($postal_code == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$postal_code.'\',';
      }

      if($address == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$address.'\',';
      }

      if($telnumber == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$telnumber.'\',';
      }

      if($delivery_slip_flag == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$delivery_slip_flag.'\',';
      }

      if($myhouse_flag == '')
      {
        $param .= '\'\'';
      }
      else
      {
        $param .= '\''.$myhouse_flag.'\'';
      }

      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        $ret = $JointPrc->callProc('proc_InsertOrderPostAddress',$param);

        //注文処理中の注文情報の取得
        $param  = '';
        if($member_id == '')
        {
          $param .= 'NULL';
        }
        else
        {
          $param .= '\''.$member_id.'\'';
        }


        $order_ret = $JointPrc->callProc('proc_SelectOrderPostAddressList',$param);
        echo json_encode($param);
      }
    }
  }
?>