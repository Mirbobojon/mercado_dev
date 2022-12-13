<?php
  require './common/common.php';
  $select_checkbox_value = [];
  $member_id = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'bulk_operationkey')
  {

    if(isset($_POST['select_checkbox_value']))
    {
      $select_checkbox_value = json_decode($_POST['select_checkbox_value']);
    }

    $array_length = count($select_checkbox_value);
    for($i=0;$i<$array_length;$i++)
    {
      //プロシージャに渡す引数（パラメータ）の作成
      $param = '';

      if($select_checkbox_value[$i] == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$select_checkbox_value[$i].'\',';
      }
      $param .= '\''.$_SESSION['member_id'].'\'';

      $db = new MySQL(); // DBに接続する
      if(!$db->connect())
      {
          echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
      }
      else
      {
        $JointPrc = new JointPrc (); // JointPrc呼び出し部分

        //呼び出し
        $ret = $JointPrc->callProc('proc_InsertPostAddressFromDeleveryAddress', $param);
        echo json_encode($ret);
      }
    }
  }


?>