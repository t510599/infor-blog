<?php
require_once('connection/SQL.php');
require_once('config.php');

if((isset($_POST['username']))&&(isset($_POST['password']))&&($_POST['username']!='')&&($_POST['password']!='')){
	if(login($_POST['username'],$_POST['password'])==1){
		header('Location: index.php?ok=login');
		exit;
	}else{
		header('Location: index.php?err=login');
		exit;
    }
}
?>