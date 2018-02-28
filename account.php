<?php
require_once('connection/SQL.php');
require_once('config.php');
require_once('include/view.php');

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name'])){
	$_POST['username']=strtolower($_POST['username']);
	$exist = getResult("SELECT * FROM `user` WHERE `username`='%s'",array($_POST['username']))['num_rows'];
	if($exist==0){
		$SQL->query("INSERT INTO `user` (`username`, `pwd`, `name`) VALUES ('%s', '%s', '%s')",array($_POST['username'],pwd($_POST['password'],$_POST['username']),$_POST['name']);
        header('Location: index.php?ok=reg');
        exit;
	} else {
        header('Location: account.php?new&err=lost');
        exit;
	}
} else if (isset($_SESSION['username']) && isset($_POST['username']) && isset($_POST['old']) && (isset($_POST['name']) || isset($_POST['new']))){
    $_POST['username'] = strtolower($_POST['username']);
    if ($_POST['username'] != $_SESSION['username']){
        header('Location: account.php?err=edit');
        exit;
    } else {
        $original = getResult("SELECT * FROM `user` WHERE `username`='%s'",array($_POST['username']));
        if (pwd($_POST['old'],$_POST['username']) != $original['row']['pwd'] || $original['num_rows'] == 0){
            header('Location: account.php?err=old');
            exit;
        } else {
            $passwd = pwd($_POST['new'],$_POST['username'])
            $SQL->query("UPDATE `user` SET `name`='%s', `pwd`='%s' WHERE `username`='%s'",array($_POST['name'],$passwd,$_POST['username']));
            header('Location: account.php?ok=edit');
            exit;
        }
    }
}

if (!isset($_SESSION['username']) && !isset($_GET['new'])) {
    header('Location: account.php?new');
    exit;
}

if (isset($_GET['new'])){
    if (isset($_GET['err'])){
        if ($_GET['err'] == "edit"){

        } else if ($_GET['err'] == "old"){

        } else if ($_GET['err'] == "used"){

        } else if ($_GET['err'] == "miss"){

        }
    } else if (isset($_GET['ok'])){
        if (isset($_GET['edit'])){

        }
    }
// create 
    $view = new View('theme/default.html','theme/nav/default.html','theme/sidebar.php',$blog['site_name'],"註冊");
?>
<form action="account.php" method="POST" name="newacc">
    <div class="ts form">
        <div class="ts big dividing header">註冊</div>
        <div class="required field">
            <label>帳號</label>
            <input type="text" required="required" name="username">
            <small>你未來將無法更改這項設定。</small>
        </div>
        <div class="required field">
            <label>暱稱</label>
            <input type="text" required="required" name="name" maxlength="40">
        </div>
        <div class="required field">
            <label>密碼</label>
            <input type="password" required="required" name="password">
        </div>
        <a class="ts primary button" style="margin:5px 5px; float:right;" href="javascript:createAccount()">
            送出
        </a>
        <script>function createAccount() {document.newacc.submit();}</script>
    </div>
</form>
<?php
    $view->render();
} else {
//edit
    $username = $_SESSION['username'];
    $name = getResult("SELECT `name` FROM `user` WHERE `username`=`%s`",array($username));
    $name = $name['row']['name'];;
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['site_name'],"帳號");
?>
<form action="account.php" method="POST" name="editacc">
    <div class="ts form">
        <div class="ts big dividing header">編輯帳號</div>
        <div class="disabled field">
            <label>帳號</label>
            <input type="text" name="username" value="<?php echo $username;?>">
        </div>
        <div class="required field">
            <label>暱稱</label>
            <input type="text" required="required" name="name" maxlength="40" value="<?php echo $name;?>">
        </div>
        <div class="required field">
            <label>舊密碼</label>
            <input type="password" required="required" name="old">
        </div>
        <div class="field">
            <label>新密碼</label>
            <input type="password" name="new">
            <small>留空則不修改。</small>
        </div>
        <a class="ts primary button" style="margin:5px 5px; float:right;" href="javascript:editAccount()">
            送出
        </a>
        <script>function editAccount() {document.editacc.submit();}</script>
    </div>
</form>
<? $view->render();
}
?>