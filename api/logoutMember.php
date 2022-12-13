<?php
  require './common/common.php';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'logoutkey')
  {
    //ログインセッションの削除
    unset($_SESSION['member_id']);
  }
?>