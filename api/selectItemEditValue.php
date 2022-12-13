<?php
  require './common/common.php';
  $limit = -1;
  $offset = 0;

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['limit']))
    {
      $limit = htmlspecialchars($_POST['limit'], ENT_QUOTES);
    }
    if(isset($_POST['offset']))
    {
      $offset = htmlspecialchars($_POST['offset'], ENT_QUOTES);
    }

    //プロシージャに渡す引数（パラメータ）の作成
    $param = '';

    if($limit == '')
    {
      $param .= 'NULL,';
    }
    else
    {
      $param .= '\''.$limit.'\',';
    }

    if($offset == '')
    {
      $param .= 'NULL';
    }
    else
    {
      $param .= '\''.$offset.'\'';
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
      $category_ret = $JointPrc->callProc('proc_SelectCategoryList', $param);
      $department_ret = $JointPrc->callProc('proc_SelectDepartmentList', $param);
      $tax_ret = $JointPrc->callProc('proc_SelectTaxList', $param);
      echo json_encode(['category' => $category_ret, 'department' => $department_ret, 'tax' => $tax_ret]);
    }

  }


?>