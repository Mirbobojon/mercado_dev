<?php
  require './common/common.php';

  $news_id = '';
  $title = '';
  $body = '';
  $status = '';
  $publication_date = '20000101';
  $publication_hour = '08';
  $publication_minutes = '00';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey')
  {
    //formデータの取得
    if(isset($_POST['news_id']))
    {
      $news_id = htmlspecialchars($_POST['news_id'], ENT_QUOTES);
    }
    if(isset($_POST['title']))
  {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
  }
  if(isset($_POST['body']))
  {
    $body = htmlspecialchars($_POST['body'], ENT_QUOTES);
  }
  if(isset($_POST['status']))
  {
    $status = htmlspecialchars($_POST['status'], ENT_QUOTES);
  }
  if(isset($_POST['publication_date']))
  {
    $publication_date = htmlspecialchars($_POST['publication_date'], ENT_QUOTES);
  }
  if(isset($_POST['publication_hour']))
  {
    $publication_hour = htmlspecialchars($_POST['publication_hour'], ENT_QUOTES);
  }
  if(isset($_POST['publication_minutes']))
  {
    $publication_minutes = htmlspecialchars($_POST['publication_minutes'], ENT_QUOTES);
  }

  //公開日時の作成
  $publication_datetime = $publication_date.' '.$publication_hour.':'.$publication_minutes;

    //商品情報の挿入
    //引数（パラメータ）の作成
    $param = '';

    //パラメータの挿入
    if($news_id == '')
    {
      $param .= '\'\',';
    }
    else
    {
      $param .= '\''.$news_id.'\',';
    }

    if($title == '')
  {
    $param .= '\'\',';
  }
  else
  {
    $param .= '\''.$title.'\',';
  }

  if($body == '')
  {
    $param .= 'NULL,';
  }
  else
  {
    $param .= '\''.$body.'\',';
  }

  if($status == '')
  {
    $param .= '\'0\',';
  }
  else
  {
    $param .= '\''.$status.'\',';
  }

  if($publication_datetime == '')
  {
    $param .= '\'\'';
  }
  else
  {
    $param .= '\''.$publication_datetime.'\'';
  }

    $db = new MySQL(); // DBに接続する
    if(!$db->connect())
    {
        echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
    }
    else
    {
      $JointPrc = new JointPrc (); // JointPrc呼び出し部分

      $ret = $JointPrc->callProc('proc_UpdateNews',$param);
      echo json_encode($ret);
      if($ret)
      {

        // //削除フラグが立っている時に画像の削除
        if(isset($_POST['delete_image_flag']) && $_POST['delete_image_flag'] == true)
        {
          if(isset($_POST['delete_image_id']) && is_array(json_decode($_POST['delete_image_id'])))
          {
            $delete_id = json_decode($_POST['delete_image_id']);
            $delete_id_count = count($delete_id);
            for($i=0;$i<$delete_id_count;$i++)
            {
              //パラメータ作成
              $param = '';
              $param .= '\''.$delete_id[$i].'\'';  //  画像ID

              $ret = $JointPrc->callProc('proc_DeleteNewsImage',$param);
            }
          }
        }

        //メイン画像の登録
        date_default_timezone_set('Asia/Tokyo');
        $nowdatetime = date('Ymdhis');
        $image_path = '';
        if(!empty($_FILES['main_image']['tmp_name']) && is_uploaded_file($_FILES['main_image']['tmp_name']))
        {
          // 保存するパスの指定
          $file_name = $nowdatetime.'.jpg';
          $image_path = NEWSDIR.'/'.$file_name;
          move_uploaded_file( $_FILES['main_image']['tmp_name'], UPDATENEWSTEMPPATH.$image_path);
          //メイン画像情報をDBに挿入
          //パラメータ作成
          $param = '';

          //パラメータの挿入
          $param .= '\''.$news_id.'\',';  //  商品ID
          $param .= '\'1\',';  //  種別 1:メイン画像
          $param .= '\'/'.$file_name.'\'';  //  画像パス

          $ret = $JointPrc->callProc('proc_InsertNewsImage',$param);
          echo json_encode($ret);
        }

          //本文内画像情報をnews_imagesに挿入
        $images_src = [];
        if(isset($_POST['imagesSrc']))
        {
          $images_src = explode(',',$_POST['imagesSrc']);
        }
        foreach($images_src as $image_src)
        {
          if($image_src != '')
          {
            //引数（パラメータ）の作成
            $param = '';

            //パラメータの挿入
            $param .= '\''.$news_id.'\',';
            $param .= '\'2\',';
            $param .= '\''.$image_src.'\'';

            $ret = $JointPrc->callProc('proc_InsertNewsImage',$param);

            echo json_encode($ret);

          }
        }
      }
      else
      {
        echo 'false';
      }
    }
  }
?>