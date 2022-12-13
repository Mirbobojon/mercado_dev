<?php
  require './common/common.php';
  $mailing_list_id= '';
  $select_checkbox_value = [];

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'bulk_operationkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['mailing_list_id']))
    {
      $mailing_list_id = htmlspecialchars($_POST['mailing_list_id'], ENT_QUOTES);
    }

    if(isset($_POST['select_checkbox_value']))
    {
      $select_checkbox_value = json_decode($_POST['select_checkbox_value']);
    }

    $array_length = count($select_checkbox_value);
    for($i=0;$i<$array_length;$i++)
    {
      //プロシージャに渡す引数（パラメータ）の作成
      $param = '';

      if($mailing_list_id == '')
      {
        $param .= '\'\',';
      }
      else
      {
        $param .= '\''.$mailing_list_id.'\',';
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
        $ret = $JointPrc->callProc('proc_InsertMailingListMember', $param);
        echo json_encode($ret);
      }
    }
  }


?>