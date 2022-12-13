<?php

// ログ出力
function write_log($data)
{
    $log = "---------------------------\n";
    $log .= print_r($data, true);
    $log .= "\n";
    file_put_contents("log.txt", $log, FILE_APPEND);
}

// ストアドプロシージャ用引数生成
function gen_proc_param($arr)
{
    if (!is_array($arr)) {
        return '';
    }

    $param = implode("','", $arr);
    $param = "'" . $param . "'";
    return $param;
}
