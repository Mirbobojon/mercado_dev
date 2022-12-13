<?php
  require './common/common.php';
  $sort = '';
  $limit = -1;
  $offset = 0;

  //セッション情報の取得処理

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'selectkey')
  {
    //ポストされたデータの取得、代入
    if(isset($_POST['sort']))
    {
      $sort = htmlspecialchars($_POST['sort'], ENT_QUOTES);
    }
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

    if($sort == '')
    {
      $param .= '\'news.insert_datetime DESC\',';
    }
    else
    {
      $param .= '\'news.'.$sort.'\',';
    }

    if($limit == '')
    {
      $param .= '\'-1\',';
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
      $ret = $JointPrc->callProc('proc_SelectPublicNewsList', $param);

      //結果を返す
      echo json_encode($ret);
    }

  }


?>