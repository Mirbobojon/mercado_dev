<?php
require './common/common.php';
// write_log($_POST);
// write_log($_FILES);

$name = '';
$category_id = '';
$recommend_flag = '';
$item_serial = '';
$standard = '';
$description = '';
$price = '';
$tax_id = '';
$postage = '';
$stock_quantity = '';
$department_id = '';
$status = '';
if (isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey') {
  //formデータの取得
  if (isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
  }
  if (isset($_POST['category_id'])) {
    $category_id = htmlspecialchars($_POST['category_id'], ENT_QUOTES);
  }
  if (isset($_POST['recommend_flag'])) {
    $recommend_flag = htmlspecialchars($_POST['recommend_flag'], ENT_QUOTES);
  }
  if (isset($_POST['item_serial'])) {
    $item_serial = htmlspecialchars($_POST['item_serial'], ENT_QUOTES);
  }
  if (isset($_POST['standard'])) {
    $standard = htmlspecialchars($_POST['standard'], ENT_QUOTES);
  }
  if (isset($_POST['description'])) {
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES);
  }
  if (isset($_POST['price'])) {
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES);
  }
  if (isset($_POST['tax_id'])) {
    $tax_id = htmlspecialchars($_POST['tax_id'], ENT_QUOTES);
  }
  if (isset($_POST['postage'])) {
    $postage = htmlspecialchars($_POST['postage'], ENT_QUOTES);
  }
  if (isset($_POST['stock_quantity'])) {
    $stock_quantity = htmlspecialchars($_POST['stock_quantity'], ENT_QUOTES);
  }
  if (isset($_POST['department_id'])) {
    $department_id = htmlspecialchars($_POST['department_id'], ENT_QUOTES);
  }
  if (isset($_POST['status'])) {
    $status = htmlspecialchars($_POST['status'], ENT_QUOTES);
  }

  //商品情報の挿入
  //引数（パラメータ）の作成
  $param = '';

  //パラメータの挿入
  if ($name == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $name . '\',';
  }

  if ($category_id == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $category_id . '\',';
  }

  if ($recommend_flag == '') {
    $param .= '\'0\',';
  } else {
    $param .= '\'' . $recommend_flag . '\',';
  }

  if ($item_serial == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $item_serial . '\',';
  }

  if ($standard == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $standard . '\',';
  }

  if ($description == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $description . '\',';
  }

  if ($price == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $price . '\',';
  }

  if ($tax_id == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $tax_id . '\',';
  }

  if ($postage == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $postage . '\',';
  }

  if ($stock_quantity == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $stock_quantity . '\',';
  }

  if ($department_id == '') {
    $param .= 'NULL,';
  } else {
    $param .= '\'' . $department_id . '\',';
  }

  if ($status == '') {
    $param .= '\'\'';
  } else {
    $param .= '\'' . $status . '\'';
  }

  $db = new MySQL(); // DBに接続する
  if (!$db->connect()) {
    echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
  } else {
    $JointPrc = new JointPrc(); // JointPrc呼び出し部分

    $ret = $JointPrc->callProc('proc_InsertItem', $param);
    if ($ret) {
      //登録した商品のidを取得
      $item_id = $ret[0]['LAST_INSERT_ID()'];

      // //メイン画像の登録
      date_default_timezone_set('Asia/Tokyo');
      $nowdatetime = date('YmdHis');
      $image_path = '';
      if (!empty($_FILES['main_image']['name'])) {
        if (
          is_uploaded_file($_FILES['main_image']['tmp_name'])
          && $_FILES['main_image']['error'] === 0
        ) {

          // 保存するパスの指定
          $file_name = $nowdatetime . '.jpg';
          $image_path = ITEMDIR . '/' . $file_name;
          move_uploaded_file($_FILES['main_image']['tmp_name'], UPDATEITEMSDIRTEMPPATH . '/' . $file_name);
          //メイン画像情報をDBに挿入
          //パラメータ作成
          $param = '';

          //パラメータの挿入
          $param .= '\'' . $item_id . '\',';  //  商品ID
          $param .= '\'1\',';  //  種別 1:メイン画像
          $param .= '\'/' . $file_name . '\'';  //  画像パス

          $ret = $JointPrc->callProc('proc_InsertItemImage', $param);
        } else {
          echo 'false';
          exit();
        }
      }

      //商品画像の登録
      for ($i = 1; $i < 5; $i++) {
        $image_path = '';
        if (!empty($_FILES['image_' . $i]['name'])) {
          if (
            is_uploaded_file($_FILES['image_' . $i]['tmp_name'])
            && $_FILES['image_' . $i]['error'] === 0
          ) {

            // 保存するパスの指定
            $file_name = $nowdatetime . '_' . $i . '.jpg';
            $image_path = ITEMDIR . '/' . $file_name;
            move_uploaded_file($_FILES['image_' . $i]['tmp_name'], UPDATEITEMSDIRTEMPPATH . '/' . $file_name);

            //メイン画像情報をDBに挿入
            //パラメータ作成
            $param = '';

            //パラメータの挿入
            $param .= '\'' . $item_id . '\',';  //  商品ID
            $param .= '\'2\',';  //  種別 1:商品画像
            $param .= '\'/' . $file_name . '\'';  //  画像パス

            $ret = $JointPrc->callProc('proc_InsertItemImage', $param);
          } else {
            echo 'false';
            exit();
          }
        }
      }
      echo 'true';
    } else {
      echo 'false';
    }
  }
} else {
  echo 'false';
}
