<?php
require './common/common.php';

if (isset($_POST['formkey']) && $_POST['formkey'] == 'sendkey') {
  //formデータの取得
  if (isset($_POST['mail_address'])) {
    $mail_address = htmlspecialchars($_POST['mail_address'], ENT_QUOTES);
  } else {
    echo 'error';
    exit();
  }

  //シリアルナンバー作成(40文字)
  $serial = substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, 40);
  $admin_flag = 0;

  $db = new MySQL(); // DBに接続する
  if (!$db->connect()) {
    echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
  } else {
    $JointPrc = new JointPrc(); // JointPrc呼び出し部分
    $in1 = gen_proc_param([$serial, $admin_flag, $mail_address]);
    $res1 = $JointPrc->callProc('proc_InsertReissue', $in1);
    if ($res1) {
      $send_data['serial'] = $serial;
      echo json_encode($send_data);
    }
  }
}
