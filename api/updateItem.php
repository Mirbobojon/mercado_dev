<?php
require './common/common.php';
// write_log($_POST);
// write_log($_FILES);

$item_id = '';
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

if (isset($_POST['formkey']) && $_POST['formkey'] == 'updatekey') {
  //formデータの取得
  if (isset($_POST['item_id'])) {
    $item_id = htmlspecialchars($_POST['item_id'], ENT_QUOTES);
  }
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
  if ($item_id == '') {
    $param .= '\'\',';
  } else {
    $param .= '\'' . $item_id . '\',';
  }

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

    $ret = $JointPrc->callProc('proc_UpdateItem', $param);
    if ($ret) {

      // 削除フラグが立っている時に画像の削除
      if (isset($_POST['delete_image_flag']) && $_POST['delete_image_flag'] == true) {
        if (isset($_POST['delete_image_id']) && is_array(json_decode($_POST['delete_image_id']))) {
          $delete_id = json_decode($_POST['delete_image_id']);
          $delete_id_count = count($delete_id);
          for ($i = 0; $i < $delete_id_count; $i++) {
            $image_id = $delete_id[$i];

            // 削除画像パスを取得
            $in1 = gen_proc_param([$image_id, $item_id]);
            $res1 = $JointPrc->callProc('proc_SelectItemImagePath', $in1);
            if ($res1) {
              // 画像ファイル削除
              $image_path = UPDATEITEMSDIRTEMPPATH . $res1[0]['path'];
              if (unlink($image_path)) {
                // write_log("deleted: $image_path");
              } else {
                write_log("updateItem.php: failed to delete: $image_path");
              }
            }

            // 商品画像情報削除
            $in2 = gen_proc_param([$image_id]);
            $res2 = $JointPrc->callProc('proc_DeleteItemImage', $in2);
          }
        }
      }

      //メイン画像の登録
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

          //画像情報をDBに挿入
          $kinds = 1; /* 種別：メイン画像 */
          $in3 = gen_proc_param([$item_id, $kinds, "/" . $file_name]);
          $ret = $JointPrc->callProc('proc_InsertItemImage', $in3);
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

            //画像情報をDBに挿入
            $kinds = 2; /* 種別：画像 */
            $in3 = gen_proc_param([$item_id, $kinds, "/" . $file_name]);
            $ret = $JointPrc->callProc('proc_InsertItemImage', $in3);
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
