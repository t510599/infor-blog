<?php
require_once('connection/SQL.php');
require_once('config.php');
require_once('include/view.php');

if(isset($_SESSION['username']) && isset($_POST['pid']) && isset($_POST['title']) && isset($_POST['content'])){
    $level = getResult("SELECT `level` FROM `user` WHERE `username` = '%s'",array($_SESSION['username']))['row']['level'];
    if (intval($level) < 1) {
        header('Location: post.php');
        exit;
    }
    if($_POST['pid'] == "-1"){
        $current = date('Y-m-d H:i:s');
        $SQL->query("INSERT INTO `post` (`title`, `content`, `time`, `username`) VALUES ('%s', '%s', '%s', '%s')",array(htmlspecialchars($_POST['title']),htmlspecialchars($_POST['content']),$current,$_SESSION['username']));
        $pid = getResult("SELECT `pid` FROM `post` WHERE `time` = '%s'",array($current))['row']['pid'];
        header('Location: post.php?pid='.$pid);
        exit;
    } else {
        $author = getResult("SELECT `username` FROM `post` WHERE `pid` = '%d'",array($_POST['pid']))['row']['username'];
        if ($author != $_SESSION['username']){
            header('Location: post.php?err=edit');
            exit;
        }
        $SQL->query("UPDATE `post` SET `title`='%s', `content`='%s' WHERE `pid`='%d' AND `username`='%s'",array(htmlspecialchars($_POST['title']),htmlspecialchars($_POST['content']),abs($_POST['pid']),$_SESSION['username']));
        header('Location: post.php?pid='.$_POST['pid']);
        exit;
    }
}

if(isset($_SESSION['username'])){
    $islogin = true;
} else {
    $islogin = false;
}

if($islogin && isset($_GET['del']) && trim($_GET['del'])!=''){
    $author = getResult("SELECT `username` FROM `post` WHERE `pid` = '%d'",array($_GET['del']))['row']['username'];
    if ($author != $_SESSION['username']) {
        header('Location: post.php?err=del');
        exit;
    } else {
        $SQL->query("DELETE FROM `post` WHERE `pid`='%d' AND `username` = '%s'",array($_GET['del'],$_SESSION['username']));
        $SQL->query("DELETE FROM `like` WHERE `pid`='%d'",array($_GET['del']));
        header('Location: post.php?ok=del');
        exit;
    }
} else if (!$islogin && isset($_GET['del'])) {
    header('Location: index.php?err=nologin');
    exit;
}

// View
if(isset($_GET['pid'])){
    $pid = abs($_GET['pid']);
    $post = getResult("SELECT * FROM `post` WHERE `pid`=%d",array($pid));
    if($post['num_rows']<1){ // not found post
        header('Location: index.php?err=post');
        exit;
    }
    // get post data
    $name = getResult("SELECT `name` FROM `user` WHERE `username`='%s'",array($post['row']['username']))['row']['name'];
    $title = $post['row']['title'];
    $content = $post['row']['content'];
    $time = $post['row']['time'];
    $likes = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid));
    $islike = false;
    if ($likes['num_rows']>0 && isset($_SESSION['username'])){
        do {
            if($_SESSION['username'] == $likes['row']['username']){
                $islike = true;
            }
        }while($likes['row'] = $likes['query']->fetch_assoc());
    }
    if ($likes['num_rows']<1){
        $likes = 0;
    } else {
        $likes = $likes['num_rows'];
    }
    if($islogin){
        $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],$title);
        $view->addScript("<script>ts('.ts.dropdown:not(.basic)').dropdown();</script>");
        $view->addScript('<script src="./include/js/markdown.js"></script>');
        $view->addScript('<script src="./include/js/like.js"></script>');
    } else {
        $view = new View('theme/default.html','theme/nav/default.html','theme/sidebar.php',$blog['name'],$title);
        $view->addScript('<script src="./include/js/markdown.js"></script>');
        $view->addScript('<script src="./include/js/like.js"></script>');
    }
    ?>
    <div class="ts stackable grid">
        <div class="stretched column">
            <h1 style="margin-left:5px;word-break:break-all;"><?php echo $title;?></h1>
        </div>
        <div class="column">
            <div class="ts buttons">
                <button class="ts secondary icon button" id="like" data-id="<?php echo $pid;?>">
                    <i class="thumbs <?php if(!$islike){echo "outline";}?> up icon"></i> <?php echo $likes;?>
                </button>
                <?php
                if (isset($_SESSION['username'])) { 
                    if ($post['row']['username'] == $_SESSION['username']) {?>
                <a class="ts secondary icon button" href="post.php?edit=<?php echo $pid;?>">
                    <i class="edit icon"></i>
                </a>
                <a class="ts secondary icon button" href="post.php?del=<?php echo $pid;?>">
                    <i class="trash icon"></i>
                </a>
                <?php } 
                } ?>
            </div>
        </div>
    </div>
    <div class="ts segments">
        <div class="ts flatted segment" id="markdown" style="font-size:15px; line-height:1.8em;">
<?php echo $content;?>
        </div>
        <div class="ts tertiary segment">
            <i class="user icon"></i> <?php echo $name;?> <i class="calendar icon"></i> <?php echo $time;?>
        </div>
    </div>
    <?php $view->render();

} else if (isset($_GET['new'])) {
// New
    if (!$islogin){
        header('Location: index.php?err=nologin');
        exit;
    }
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],"文章");
    $level = getResult("SELECT `level` FROM `user` WHERE `username` = '%s'",array($_SESSION['username']))['row']['level'];
    if (intval($level) < 1) { ?>
<div class="ts inverted negative message">
    <p>你沒有權限發文!</p>
</div>
    <?php
        $view->render();
        exit;
    }
    $view->addScript('<script src="./include/js/markdown.js"></script>');    
    $view->addScript('<script src="./include/js/edit.js"></script>');
    $view->addScript("<script>ts('.ts.dropdown:not(.basic)').dropdown();textarea.value = txtTrim(textarea.value);</script>");

    ?>
<form action="post.php" method="POST" name="edit" id="edit">
    <div class="ts stackable grid">
        <div class="stretched column">
            <div class="ts huge fluid underlined input">
                <input placeholder="標題" name="title" value="">
            </div>
        </div>
        <div class="column" style="display:flex; justify-content:center; align-items:center;">
            <div class="ts buttons">
                <a href="javascript:post();" class="ts positive button">發布</a>
                <a href="index.php" class="ts button">取消</a>
            </div>
        </div>
    </div>
    <div class="editor-wrapper">
        <textarea id="markdown" name="content"></textarea>
    </div>
    <input type="hidden" name="pid" id="pid "value="-1">
</form>
    <?php $view->render();
} else if (isset($_GET['edit'])) {
// Edit
    if (!$islogin){
        header('Location: index.php?err=nologin');
        exit;
    }
    $level = getResult("SELECT `level` FROM `user` WHERE `username` = '%s'",array($_SESSION['username']))['row']['level'];
    if (intval($level) != 1) {
        $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],"文章"); ?>
<div class="ts inverted negative message">
    <p>你沒有權限發文!</p>
</div>
    <?php
        $view->render();
        exit;
    }
    $pid = abs($_GET['edit']);
    $post = getResult("SELECT * FROM `post` WHERE `pid`=%d",array($pid));
    if($post['num_rows']<1){ // not found post
        header('Location: index.php?err=post');
        exit;
    }
    if($post['row']['username'] != $_SESSION['username']){
        header('Location: post.php?err=edit');
        exit;
    }
    // get post data
    $title = $post['row']['title'];
    $content = $post['row']['content'];
    $time = $post['row']['time'];
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],$title);
    $view->addScript('<script src="./include/js/markdown.js"></script>');
    $view->addScript('<script src="./include/js/edit.js"></script>');
    $view->addScript("<script>ts('.ts.dropdown:not(.basic)').dropdown();textarea.value = txtTrim(textarea.value);</script>");
?>
<form action="post.php" method="POST" name="edit" id="edit">
    <div class="ts stackable grid">
        <div class="stretched column">
            <div class="ts huge fluid underlined input">
                <input placeholder="標題" name="title" value="<?php echo $title;?>">
            </div>
        </div>
        <div class="column" style="display:flex; justify-content:center; align-items:center;">
            <div class="ts buttons">
                <a href="javascript:post();" class="ts positive button">發布</a>
                <a href="post.php?del=<?php echo $pid;?>" class="ts negative button">刪除</a>
                <a href="index.php" class="ts button">取消</a>
            </div>
        </div>
    </div>
    <div class="editor-wrapper">
        <textarea id="markdown" name="content"><?php echo $content;?></textarea>
    </div>
    <input type="hidden" name="pid" id="pid "value="<?php echo $pid;?>">
</form>
    <?php $view->render();
} else {
// List all
    if(!$islogin){
        header('Location: index.php?err=nologin');
        exit;
    }
    $post_list=getResult("SELECT * FROM `post` WHERE `username`='%s' ORDER BY `time`",array($_SESSION['username']));
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],"文章");
    $view->addScript("<script>ts('.ts.dropdown').dropdown();ts('.ts.sortable.table').tablesort();</script>");
    if (isset($_GET['ok'])){
        if ($_GET['ok'] == "del"){ ?>
<div class="ts inverted positive message">
    <p>刪除成功</p>
</div>
    <?php }
    }
    if (isset($_GET['err'])) {
        if ($_GET['err'] == "del"){ ?>
<div class="ts inverted negative message">
    <p>刪除失敗</p>
</div>
    <?php } else if ($_GET['err'] == "edit") {?>
<div class="ts inverted negative message">
    <p>編輯失敗</p>
</div>
        <?php }
    }
?>
<div class="ts big dividing header">文章</div>
<table class="ts sortable celled striped table">
    <thead>
        <tr>
            <th>標題</th>
            <th>讚</th>
            <th>日期</th>
            <th>管理</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if($post_list['num_rows']>0){
        do {
            $author = getResult("SELECT `name` FROM `user` WHERE `username`='%s'",array($post_list['row']['username']));
            $pid = $post_list['row']['pid'];
            $title = $post_list['row']['title'];
            $time = $post_list['row']['time'];
            $likes = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid));
            if ($likes['num_rows']<1) {
                $likes = 0;
            } else {
                $likes = $likes['num_rows'];
            }
    ?>
        <tr>
            <td><a href="post.php?pid=<?php echo $pid;?>"><?php echo $title;?></a></td>
            <td class="collapsing"><?php echo $likes;?></td>
            <td class="collapsing"><?php echo $time;?></td>
            <td class="right aligned collapsing">
                <a class="ts primary button" href="post.php?edit=<?php echo $pid;?>">編輯</a>
                <a class="ts negative button" href="post.php?del=<?php echo $pid;?>">刪除</a>
            </td>
        </tr>
    <?php }while($post_list['row'] = $post_list['query']->fetch_assoc()); ?>
    </tbody>
</table>
    <?php } else {?>
        <tr>
            <td colspan="4">沒有文章</td>
        <tr>
    </tbody>
</table>
    <?php } 
    $view->render();
}
?>