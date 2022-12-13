<?php
  require './common/common.php';

  $member_id = 12;
  $id = '';
  $family_name = '';
  $first_name = '';
  $family_name_furigana = '';
  $first_name_furigana = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_SESSION['member_id']))
    {
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);
    }
    if(isset($_POST['id']))
    {
      $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
    }
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

    //商品情報の挿入
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

    if($id == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$id.'\',';
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
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$telnumber.'\'';
    }


    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_UpdateDelivereAddress',$param);
      echo json_encode($ret);
    }
  }
?>