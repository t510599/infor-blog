<?php
require_once('include/function.php');
if(!session_id()) {
	session_start();
}

global $blog;

date_default_timezone_set("Asia/Taipei");
$blog['name'] = '石頭洞'; //網站名稱
$blog['limit']='10';//首頁顯示文章數量
?>