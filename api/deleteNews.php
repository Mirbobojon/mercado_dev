<?php
  require './common/common.php';
  $news_id = '';
  $auth = '';

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['news_id']))
    {
      $news_id = htmlspecialchars($_POST['news_id'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($news_id == '')
    {
      $param .= '\'\'';
    }
    else
    {
      $param .= '\''.$news_id.'\'';
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
      $ret = $JointPrc->callProc('proc_DeleteNews', $param);

      //サーバから画像ファイルを削除
      $ret_length = count($ret);
      for($i=0;$i<$ret_length;$i++)
      {
        $file_name = $ret[$i]['path'];
        if($ret[$i]['kinds'] == 1)
        {
          $image_path = '../../'.IMAGEPATH.$file_name;
        }
        else
        {
          $image_path = '../../'.$file_name;
        }
        if(file_exists($image_path)){
          unlink($image_path);
        }
      }

      //結果を返す
      echo json_encode($ret);
    }

  }


?>