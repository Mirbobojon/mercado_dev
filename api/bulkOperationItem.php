<?php
  require './common/common.php';
  $select_value= '';
  $select_checkbox_value = [];
  $auth ='';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'bulk_operationkey')
  {
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

        if($select_checkbox_value[$i] == '')
        {
          $param .= '\'\',';
        }
        else
        {
          $param .= '\''.$select_checkbox_value[$i].'\',';
        }

        if($auth == '')
        {
          $param .= '\'\'';
        }
        else
        {
          $param .= '\''.$auth.'\'';
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
          $ret = $JointPrc->callProc('proc_DeleteItem', $param);

          //サーバから画像ファイルを削除
          $ret_length = count($ret);
          for($j=0;$j<$ret_length;$j++)
          {
            $file_name = $ret[$j]['path'];
            $image_path = '../../'.ITEMIMAGEPATH.$file_name;
            if(file_exists($image_path)){
              unlink($image_path);
            }
          }
        }
      }
    }
    elseif($select_value == 'draft' || $select_value == 'public' || $select_value == 'privete')
    {
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

        $param .= '\''.$select_value.'\',';

        if($auth == '')
        {
          $param .= '\'\'';
        }
        else
        {
          $param .= '\''.$auth.'\'';
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
          $ret = $JointPrc->callProc('proc_UpdateItemStatus', $param);
          echo json_encode($ret);
        }
      }
    }
  }


?>