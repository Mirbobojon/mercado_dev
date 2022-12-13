<?php
require './common/common.php';
$item_id = '';
$auth = '';

//セッション情報の取得処理

if (isset($_POST['formkey']) && $_POST['formkey'] == 'deletekey') {
  //ポストされたデータの取得、代入
  if (isset($_POST['item_id'])) {
    $item_id = htmlspecialchars($_POST['item_id'], ENT_QUOTES);
  }

  //プロシージャに渡す引数（パラメータ）の作成
  $param = '';

  if ($item_id == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $item_id . '\',';
  }

  if ($auth == '') {
    $param .= '\'\'';
  } else {
    $param .= '\'' . $auth . '\'';
  }

  $db = new MySQL(); // DBに接続する
  if (!$db->connect()) {
    echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
  } else {
    $JointPrc = new JointPrc(); // JointPrc呼び出し部分

    //呼び出し
    $ret = $JointPrc->callProc('proc_DeleteItem', $param);
    // write_log($ret);
    if ($ret == 'FALSE') {
      echo 'error';
    } else {
      // if ($ret) {
      //サーバから画像ファイルを削除
      $ret_length = count($ret);
      for ($i = 0; $i < $ret_length; $i++) {
        $file_name = $ret[$i]['path'];
        $image_path = UPDATEITEMSDIRTEMPPATH . $file_name;
        // $image_path = '../../'.IMAGEPATH.$file_name;
        if (file_exists($image_path)) {
          if (!unlink($image_path)) {
            write_log("deleteItem.php: failed to delete: " . $image_path);
          };
        }
      }
      echo 'true';
      // } else {
      //   echo 'error';
      // }
    }

    //結果を返す
    // echo json_encode($ret);
  }
}
