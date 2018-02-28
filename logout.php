<?php
require_once('connection/SQL.php');
require_once('config.php');

logout();
header('Location: index.php?logout');
exit;
?>