<?php
  require './common/common.php';
  $member_id = '';
  $select_value= '';
  $select_checkbox_value = [];

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'bulk_operationkey')
  {
    if(isset($_SESSION['member_id']))
    {
      $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);
    }
    //ポストされたデータの取得、代入
    if(isset($_POST['select_value']))
    {
      $select_value = htmlspecialchars($_POST['select_value'], ENT_QUOTES);
    }

    if(isset($_POST['select_checkbox_value']))
    {
      $select_checkbox_value = json_decode($_POST['select_checkbox_value']);
    }

    if($select_value == 'delete')
    {
      $array_length = count($select_checkbox_value);
      for($i=0;$i<$array_length;$i++)
      {
        //プロシージャに渡す引数（パラメータ）の作成
        $param = '';

        if($member_id == '')
        {
          $param .= 'NULL, ';
        }
        else
        {
          $param .= '\''.$member_id.'\',';
        }

        if($select_checkbox_value[$i] == '')
        {
          $param .= '\'\'';
        }
        else
        {
          $param .= '\''.$select_checkbox_value[$i].'\'';
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
          $ret = $JointPrc->callProc('proc_DeleteDeliveryAddress', $param);
          echo json_encode($ret);
        }
      }
    }
  }


?>