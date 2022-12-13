<?php
  require './common/common.php';

  $select_value= '';
  $select_checkbox_value = [];

  $member_id = '';
  $family_name = '';
  $first_name = '';
  $family_name_furigana = '';
  $first_name_furigana = '';
  $postal_code = '';
  $address = '';
  $telnumber = '';

  if(isset($_POST['formkey']) && $_POST['formkey'] == 'insertkey')
  {
    if(!isset($_SESSION['member_id'])){
      echo 'nologin';
    }
    else
    {
      if(isset($_SESSION['member_id']))
      {
        $member_id = htmlspecialchars($_SESSION['member_id'], ENT_QUOTES);
      }
      //ポストされたデータの取得、代入
      if(isset($_POST['select_value']))
      {
        $select_value = htmlspecialchars($_POST['select_value'], ENT_QUOTES);
      }

      if(isset($_POST['select_checkbox_value']))
      {
        $select_checkbox_value = json_decode($_POST['select_checkbox_value']);
      }
      if($select_value == 'insert')
      {
        $array_length = count($select_checkbox_value);
        for($i=0;$i<$array_length;$i++)
        {
          //formデータの取得
          if(isset($select_checkbox_value[$i]->delivery_family_name))
          {
            $family_name = htmlspecialchars($select_checkbox_value[$i]->delivery_family_name, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_first_name))
          {
            $first_name = htmlspecialchars($select_checkbox_value[$i]->delivery_first_name, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_family_name_furigana))
          {
            $family_name_furigana = htmlspecialchars($select_checkbox_value[$i]->delivery_family_name_furigana, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_first_name_furigana))
          {
            $first_name_furigana = htmlspecialchars($select_checkbox_value[$i]->delivery_first_name_furigana, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_postal_code))
          {
            $postal_code = htmlspecialchars($select_checkbox_value[$i]->delivery_postal_code, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_address))
          {
            $address = htmlspecialchars($select_checkbox_value[$i]->delivery_address, ENT_QUOTES);
          }
          if(isset($select_checkbox_value[$i]->delivery_telnumber))
          {
            $telnumber = htmlspecialchars($select_checkbox_value[$i]->delivery_telnumber, ENT_QUOTES);
          }

          //引数（パラメータ）の作成
          $param = '';

          //パラメータの挿入
          if($member_id == '')
          {
            $param .= 'NULL,';
          }
          else
          {
            $param .= '\''.$member_id.'\',';
          }

          if($family_name == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$family_name.'\',';
          }

          if($first_name == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$first_name.'\',';
          }

          if($family_name_furigana == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$family_name_furigana.'\',';
          }

          if($first_name_furigana == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$first_name_furigana.'\',';
          }

          if($postal_code == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$postal_code.'\',';
          }

          if($address == '')
          {
            $param .= '\'\',';
          }
          else
          {
            $param .= '\''.$address.'\',';
          }

          if($telnumber == '')
          {
            $param .= '\'\'';
          }
          else
          {
            $param .= '\''.$telnumber.'\'';
          }

          $db = new MySQL(); // DBに接続する
          if(!$db->connect())
          {
              echo 'DB connect error';  // 接続に失敗した場合はエラーを吐き出す
          }
          else
          {
            $JointPrc = new JointPrc (); // JointPrc呼び出し部分

            $ret = $JointPrc->callProc('proc_InsertDelivereAddress',$param);
          }
        }
        echo 'done';
      }
      else
      {
        echo 'NG';
      }
    }
  }
?>