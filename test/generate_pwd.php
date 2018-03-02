<?php
require_once('../include/function.php');

if (isset($_POST['username'])&&isset($_POST['password'])) {
    echo pwd($_POST['password'],$_POST['username']);
} else {
    echo "error";
}
?>