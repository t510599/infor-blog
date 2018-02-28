<?php
function login($_username,$_password){ // modified from secret blog function.php:sb_login()
	global $SQL;
	if (isset($_username)&&isset($_password)) {
		$_username=strtolower($_username);
        $login = $SQL->query("SELECT `username`, `pwd` FROM `user` WHERE `username` = '%s' AND `pwd` = '%s'",array($_username,pwd($_password,$_username)));
		if ($login->num_rows > 0) {
			$info = $login->fetch_assoc();
			$_SESSION['username'] = $_username;      
			setcookie("login", time(), time()+10800);
			return 1;
		}
		else {
			return -1;
		}
	}
}

function logout(){ // modified from secret blog function.php:sb_loginout()
	$_SESSION['username'] = NULL;
	$_SESSION['name'] = NULL;
	unset($_SESSION['username']);
	setcookie("login", "", time()-10800);
	return 1;
}

function pwd($_value,$_salt){ // from secret blog function.php:sb_password()
	$salt=substr(sha1(strrev($_value).$_salt),0,24);
	return hash('sha512',$salt.$_value);
}

function getResult($_query,$_value=array()){ // from secret blog function.php:sb_get_result()
	global $SQL;
	$_result['query'] = $SQL->query($_query,$_value);
	$_result['row'] = $_result['query']->fetch_assoc();
	$_result['num_rows'] = $_result['query']->num_rows;
	if($_result['num_rows']>0){
		return $_result;
	}else{
		return -1;
	}
}

function pages($now_page,$total,$page_limit){
	$text.='<select onchange="location.href=this.options[this.selectedIndex].value;">';
	$now_page = abs($now_page);
	$page_num= ceil($total / $page_limit);
	for($i=1;$i<=$page_num;$i++){
		if($_now_page!=$i){
			$text.='<option value="index.php?page='.$i.'">第 '.$i.' 頁</option>';
		}else{
			$text.='<option value="index.php?page='.$i.'" selected="selected">第 '.$i.' 頁</option>';
		}
	}
	$text.='</select>';
	return $text;
}

function sumarize($string, $limit_lines){ // https://stackoverflow.com/questions/7463153/how-to-just-show-few-lines-from-a-whole-blog-post-on-a-certain-page
   $count = 0;
   $text = "";
   foreach(explode("\n", $string) as $line){
       $count++;
	   $text.=$line."\n";
       if ($count == $limit_lines) {
		   $text.="...(還有更多)\n";
		   break;
	   }
   }
   return $text;
} 