<?php
  require './common/common.php';

  if(isset($_FILES["upload"])){

    $filename = $_FILES["upload"]["name"];
    date_default_timezone_set('Asia/Tokyo');
    $nowdatetime = date('Ymdhis');
    $file_public_addr = NEWSTEMPDIR.'/'.$nowdatetime.'_'.$filename;

    $success = move_uploaded_file($_FILES["upload"]["tmp_name"], UPDATENEWSTEMPPATH.$file_public_addr);
    if( $success ){
      $json["uploaded"]=true;
      $json["url"] = $file_public_addr;
      echo json_encode($json);
    }
  }
  else
  {
    $json["uploaded"] = false;
      $json["error"] = '画像のアップロードに失敗しました。';
      echo json_encode($json);
  }

?>