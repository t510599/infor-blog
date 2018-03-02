<?php
require_once('connection/SQL.php');
require_once('config.php');

if(!isset($_GET['pid'])) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    $data = array('status' => 'error');
    echo json_encode($data);
    exit;
} else {
    $pid = abs($_GET['pid']);
    $result = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid));
    if ($result['num_rows']<0){
        $likes = 0;
    } else {
        $likes = $result['num_rows'];
    }
    if(!isset($_SESSION['username'])) { // only get likes
        $data = array('status' => false,'id' => $pid,'likes' => $likes);
    } else { // authorized user like actions
        $userliked = false;
        do {
            if($_SESSION['username'] == $result['row']['username']){
                $userliked = true;
            }
        }while($result['row'] = $result['query']->fetch_assoc());
        if($userliked){
            // unlike
            $SQL->query("DELETE FROM `like` WHERE `pid`='%d' AND `username`='%s'",array($pid,$_SESSION['username']));
            $likes = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid))['num_rows'];
            $data = array('status' => false,'id' => $pid,'likes' => $likes);
        } else {
            // like
            $SQL->query("INSERT INTO `like` (`pid`, `username`) VALUES ('%d', '%s')",array($pid,$_SESSION['username']));
            $likes = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid))['num_rows'];
            $data = array('status' => true,'id' => $pid,'likes' => $likes);
        }
    }
}

header('Content-Type: application/json');
echo json_encode($data);
exit;