<?php
require_once('connection/SQL.php');
require_once('config.php');
require_once('include/view.php');

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name'])){
	$_POST['username']=strtolower($_POST['username']);
	$exist = getResult("SELECT * FROM `user` WHERE `username`='%s'",array($_POST['username']))['num_rows'];
	if($exist==0){
		$SQL->query("INSERT INTO `user` (`username`, `pwd`, `name`) VALUES ('%s', '%s', '%s')",array($_POST['username'],pwd($_POST['password'],$_POST['username']),$_POST['name']));
        header('Location: index.php?ok=reg');
        exit;
	} else {
        header('Location: account.php?new&err=used');
        exit;
	}
} else if (isset($_SESSION['username']) && isset($_POST['username']) && isset($_POST['old']) && (isset($_POST['name']) || isset($_POST['new']))){
    $_POST['username'] = strtolower($_POST['username']);
    if ($_POST['username'] != $_SESSION['username']){
        header('Location: account.php?err=edit');
        exit;
    } else {
        $original = getResult("SELECT * FROM `user` WHERE `username`='%s'",array($_SESSION['username']));
        if (pwd($_POST['old'],$_SESSION['username']) != $original['row']['pwd'] || $original['num_rows'] == 0){
            header('Location: account.php?err=old');
            exit;
        } else {
            $passwd = pwd($_POST['new'],$_POST['username']);
            if ($_POST['new'] != ''){
                $SQL->query("UPDATE `user` SET `pwd`='%s' WHERE `username`='%s'",array($passwd,$_SESSION['username']));
            }
            if ($_POST['name'] != ''){
                $SQL->query("UPDATE `user` SET `name`='%s' WHERE `username`='%s'",array($_POST['name'],$_SESSION['username']));
            }
            header('Location: account.php?ok=edit');
            exit;
        }
    }
} else if (!isset($_SESSION['username']) && !isset($_GET['new'])) {
    header('Location: account.php?new');
    exit;
} else if (isset($_SESSION['username']) && isset($_GET['new'])) {
    header('Location: account.php');
    exit;
}

if (isset($_GET['new'])){
// create 
    $view = new View('theme/default.html','theme/nav/default.html','theme/sidebar.php',$blog['name'],"註冊");
    if (isset($_GET['err'])) {
        if ($_GET['err'] == "miss"){ ?>
<div class="ts inverted negative message">
    <p>請填寫所有欄位</p>
</div>
    <?php } else if ($_GET['err'] == "used") { ?>
<div class="ts inverted negative message">
    <p>此使用者名稱已被使用</p>
</div>
    <?php }
    }
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
        <input type="submit" class="ts primary button" style="margin:5px 5px; float:right;" value="送出">
    </div>
</form>
<?php
    $view->render();
} else {
//edit
    $username = $_SESSION['username'];
    $name = getResult("SELECT `name` FROM `user` WHERE `username`='%s'",array($username));
    $name = $name['row']['name'];;
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],"帳號");
    $view->addScript("<script>ts('.ts.dropdown').dropdown();</script>");
    if (isset($_GET['err'])){
        if ($_GET['err'] == "edit"){ ?>
<div class="ts inverted negative message">
    <p>修改失敗</p>
</div>
        <?php } else if ($_GET['err'] == "old"){ ?>
<div class="ts inverted negative message">
    <p>舊密碼錯誤</p>
</div>
        <?php }
    }
    if (isset($_GET['ok'])){
        if ($_GET['ok'] == "edit"){ ?>
<div class="ts inverted positive message">
    <p>修改成功!</p>
</div>
        <?php }
    }
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
        <input type="submit" class="ts primary button" style="margin:5px 5px; float:right;" value="送出">
        <script>function editAccount() {document.editacc.submit();}</script>
    </div>
</form>
<?php $view->render();
}
?>