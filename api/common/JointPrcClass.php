<?php
/********************************************************************/
/* 株式会社アドミン                                                 */
/*    ストアドプロシージャとPHPの連結クラス                         */
/*    @Author　山口知宏                                             */
/********************************************************************/
class JointPrc extends MySQL
{
    private $_sql = "";	// 実行されるSQL文
    private $_type = ""; // SELECT UPDATE INSERT DELETE分け

    /**
    *  ストアドプロシージャの呼び出し
    *  param1   : ストアドプロシージャ名
    *  param2～ : ストアドプロシージャへの引数
    *  全てのDBプロシージャに対応
    *  対象：INSERT, SELECT, UPDATE, DELETE
    *  return   : クエリの実行結果
    */
    public function callProc($procName , $val1=null )
    {
        try
        {
            // 第2引数を判定
            if( empty($val1) )
            {
                //指定無しOR空白でそのまま
                $this->_sql = "CALL ". $procName;
                $this->_type = "SELECT";
            }
            else
            {
                // 第2引数指定時にはプロシージャへ渡す。
                // 渡す文字列が足りなければプロシージャ側からエラーを受け取る
                $this->_sql = "CALL ". $procName. "(". $val1. ")";
                $this->_type = "SELECT";
            }
            // SELECTに対応
            if($this->_type == "SELECT")
            {
                $ret = $this->Procquery($this->_sql);
            }
            else
            {
                $ret = $this->ProcUpdquery($this->_sql);
            }

            /*
            浦﨑：
                件数チェック
            */
            // $_SESSION["field_count"] = $ret -> field_count;
            // 動いていないとおもう
            if (is_bool($ret))
            {
                return $ret;
            }
            // 値が入っていればfetchして渡す
            elseif (!empty($ret))
            {
                $retval = array();
                while ($row = mysqli_fetch_array($ret)) 
                {
                    $retval[] = $row;
                }
                // var_dump($retval);
                /* 
                浦﨑：
                    プロシージャからROLLBACKを受け取る
                    DELETEE、UPDATE
                    resultにTrue、Falseが入ってくる
                */
                if(isset($retval[0]["result"]))
                {
                    if($retval[0]["result"] == "FALSE")
                    {
                        $_SESSION["field_count"] = $ret -> field_count; // 実行件数を取得
                        // echo "SQLの実行に失敗しました。";
                        return false;
                    }
                    elseif($retval[0]["result"] == 'TRUE')
                    {
                        $_SESSION["field_count"] = $ret -> field_count; // 実行件数を取得
                        // echo "SQLの実行に成功しました";
                        return true;
                    }
                    return $retval;
                }
                return $retval;
            }
            // 上記以外はそのまま渡す
            else
            {
                return $ret;
            }
        }
        catch(Exception $e)
        {
            // var_dump('error : '.$e);
        }

    }
}