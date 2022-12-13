<?php
/********************************************************************/
/* 株式会社アドミン                                                    */
/*    MySQL操作クラス                                                 */
/*    @Author　山口知宏                                             */
/********************************************************************/

class MySQL
{
	private $_hostName;     // サーバ名
	private $_database;     // 対象DB
	private $_userName;     // DBユーザ名
	private $_password;     // DBパスワード
	private $_connection;   // DB接続判定
	private $_result;       // 結果リソース
	private $_lastSQL;      // 直前SQL
	private $_enc;          // 文字コード

	/**
	*  コンストラクタ  メンバ変数に値を代入
	*
	*/
	public function __construct($enc = "utf8")
	{
		$this->_hostName = HOSTNAME;
		$this->_userName = USERNAME;
		$this->_password = PASSWORD;
		$this->_database = DATABASE;
		$this->_connection = FALSE;
		$this->_enc = $enc;
	}

	/**
	*  デストラクタ 結果リソースの解放と接続の終了
	*
	*/
	public function __destruct()
	{
		@mysqli_free_result($this->_result);
		@mysqli_close($this->_connection);
	}

	/**
	*  MySQLへの接続とデータベースの選択 
	*
	*/
	public function connect()
	{
		$this->_connection = mysqli_connect($this->_hostName, $this->_userName, $this->_password);
		if($this->_connection)
		{
			if(mysqli_select_db($this->_connection, $this->_database))
			{
 				mysqli_set_charset($this->_connection, $this->_enc);
				$ret = TRUE;
			}
			else
			{
				$this->errorMessage("データベースの選択に失敗しました。 : ". $this->_database);
				$ret = FALSE;
			}
		}
		else
		{
			$this->errorMessage("MySQLの接続に失敗しました。");
			$ret = FALSE;
		}
		return $ret;
	}

	/**
	*  クエリーの実行
	*
	*  param : SQL文の文字列
	*  return: BOOL
	*/
	public function query($sql)
	{
		$this->_result = mysqli_query($this->_connection, $sql);

		if($this->_result)
		{
			if( (strcmp('SELECT', substr($sql,0,6))) or (strcmp('select', substr($sql,0,6))) )
			{
				$ret = $this->_result;
			}
			else
			{
				$ret = TRUE;
			}
			$this->_lastSQL = $sql;
		}
		else
		{
			$this->errorMessage("SQLの実行に失敗しました。\n$sql");
			$ret = FALSE;
		}
		
		return $ret;
	}

	/**
	*  プロシージャクエリー(SELECT)の実行
	*　　実行後にSELECT結果を取得する
	*  param : Call文の文字列
	*  return: BOOL
	*/
	public function Procquery($sql)
	{
		// echo 'query1:',$sql;

		// インスタンスを生成
		//$this->_result = mysqli_query(  $this->_connection, $sql);

		//DBを選択してコネクト
		$link = new mysqli($this->_hostName, $this->_userName, $this->_password, $this->_database);
		if ($link->connect_error)
		{
			$sql_error = $link->connect_error;
			error_log($sql_error);
			die($sql_error);
		}
		else
		{
			$link->set_charset($this->_enc);
			//$link->set_charset(DB_CHARSET);
		}

		//SQL実行
		$result = $link->query($sql);
		if (!$result)
		{
			$sql_error = $link->error;
			die($sql_error);

		}
		else
		{
		}

		//ヒット数を表示
		//$duplicate_num = $result->num_rows ;
		//$row = $result->fetch_array(MYSQLI_NUM);
		//var_dump($duplicate_num ."件該当しました。<br>") ;
		//$count = $result->field_count;
		$ret = $result;
		//接続をクローズ
		$link->close();
		return $ret;
	}

	/**
	*  セレクトクエリーの生成と実行
	*
	*  param1: 対象テーブルの文字列
	*  param2: 対象カラム配列
	*  param3: WHERE文以降の文字列
	*  return: 結果リソース 失敗時はFALSE
	*/
	public function selectQuery($table, $columns = "*", $where = null)
	{
		if($this->_connection)
		{
			$sql = "SELECT \n";
			if($columns == "*")
			{
				$sql .= "	";
				$sql .= "$columns \n";
			}
			else
			{
				foreach($columns as $value)
				{
					$sql .= "	";
					$sql .= $value. ", \n";
				}
				$sql  = substr($sql, 0,  -3);
				$sql .= " \n";
			}
			$sql .= "FROM \n";
			$sql .= "	$table \n";

			if($where != null)
			{
				$sql .= "WHERE  \n";
				$sql .= "	$where \n";
			}

			$this->_lastSQL = $sql;
			$ret = $this->query($sql);
		}
		else
		{
			$this->errorMessage('サーバーに接続されていないため、SQLの実行に失敗しました。');
			$ret = FALSE;
		}
			
		return $ret;
	}

	/**
	*  インサートクエリーの生成と実行
	*
	*  param1: 対象テーブル名の文字列
	*  param2: 挿入データ配列
	*          配列レイアウト
	*            array("column1" => array(1, "now()"),
	*                  "column2" => array(0, "str"));
	*  return: BOOL
	*/
	public function insertQuery($table, $columnArray)
	{
		if($this->_connection)
		{
			$sql = "INSERT INTO $table ";
			$columnString = "(";
			$valuesString = "VALUES(";
			foreach($columnArray as $key => $value)
			{
				$columnString .= "$key, ";
				if($value[0] == 0)
				{
					$valuesString .= "'$value[1]', ";
				}
				elseif($value[0] == 1)
				{
					$valuesString .= "$value[1], ";
				}
			}
			$columnString = substr($columnString, 0, -2);
			$valuesString = substr($valuesString, 0, -2);
			$columnString .= ") \n";
			$valuesString .= ')';
			$sql .= $columnString. $valuesString;

			$ret = $this->query($sql);
			$this->_lastSQL = $sql;
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、INSERT文の実行に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	*  アップデートクエリーの生成と実行
	*
	*  param1: 対象テーブル名の文字列
	*  param2: 挿入データ配列
	*          配列レイアウト
	*            array("column1" => array(1, "now()"),
	*                  "column2" => array(0, "str"));
	*  param3: WHERE文の文字列
	*  return: BOOL
	*/
	public function updateQuery($table, $columnArray, $where = null)
	{
		if($this->_connection)
		{
			$sql  = "UPDATE $table \n";
			$sql .= "SET \n";
			foreach($columnArray as $key => $value)
			{
				$sql .= "	$key = ";
				if($value[0] == 0)
				{
					$sql .= "'$value[1]',\n";
				}
				elseif($value[0] == 1)
				{
					$sql .= "$value[1],\n";
				}
			}
			$sql  = substr($sql, 0, -2). "\n";

			if($where != null)
			{
				$sql .= "WHERE \n";
				$sql .= '	'. $where;
			}

			$ret = $this->query($sql);
			$this->_lastSQL = $sql;
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、UPDATE文の実行に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	*  デリートクエリーの生成と実行
	*
	*  param1: 対象テーブル名の文字列
	*  param2: WHERE文の文字列
	*  return: BOOL
	*/
	public function deleteQuery($table, $where = null)
	{
		if($this->_connection)
		{
			$sql  = "DELETE FROM\n";
			$sql .= "	$table\n";

			if($where != null)
			{
				$sql .= "WHERE\n";
				$sql .= "	$where";
			}

			$ret = $this->query($sql);
			$this->_lastSQL = $sql;
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、DELETE文の実行に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	*  結果リソースからデータの取得
	*
	*  return: 1レコード分の配列 失敗時はFALSE
	*/
	public function getRecord()
	{
		if($this->_connection)
		{
			//$ret = mysql_fetch_array($this->_result);
			$ret = mysqli_fetch_array($this->_result);
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、SQLの実行結果取得に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	*  結果リソースから行数を取得
	*
	*  return: 行数 失敗時はFALSE
	*/
	public function getRecordCount()
	{
		if($this->_connection)
		{
			//$ret = mysql_num_rows($this->_result);
			$ret = mysqli_num_rows($this->_result);
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、実行結果取得に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	*  結果リソースからカラム数を取得
	*
	*  return: カラム数 失敗時はFALSE
	*/
	public function getFieldsCount()
	{
		if($this->_connection)
		{
			//$ret = mysql_num_fields($this->_result);
			$ret = mysqli_num_fields($this->_result);
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、実行結果取得に失敗しました。");
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	* MySQLの接続解除と結果リソースの解放
	*
	*/
	public function close()
	{
		if($this->_connection)
		{
			//@mysql_free_result($this->_result);
			//@mysql_close($this->_connection);
			@mysqli_free_result($this->_result);
			@mysqli_close($this->_connection);
			$this->_connection = FALSE;
			$this->_lastSQL = '';
		}
		else
		{
			$this->errorMessage("サーバーに接続されていないため、結果リソースの解放と接続の終了に失敗しました。");
		}
	}

  /**
   * 特殊文字をエスケープする
   *
   */
  public function sanitize_string($str)
  {
    return $this->_connection->real_escape_string($str);
  }

	/**
	* 最後に実行されたSQLを表示
	*
	*/
	public function printSQL()
	{
		echo "<pre>";
		print_r($this->_lastSQL);
		echo "</pre>";
	}

	/**
	* PREタグで整形されたエラーメッセージの表示
	*
	*/
	public function errorMessage($message)
	{
		echo "<pre>";
		print_r($message);
		echo "</pre>";
	}
}

?>
