<?php
require_once('connection/SQL.php');
require_once('config.php');
require_once('include/view.php');

if(isset($_GET['page'])){
	$limit_start = abs(intval(($_GET['page']-1)*$blog['list']['limit']));
	$post_list=getResult("SELECT * FROM `post` ORDER BY `time` DESC LIMIT %d,%d",array($limit_start,$blog['limit']));
} else {
	$limit_start = 0;
	$post_list=getResult("SELECT * FROM `post` ORDER BY `time` DESC LIMIT %d,%d",array($limit_start,$blog['limit']));
}

$all_post=getResult("SELECT * FROM `post`")['num_rows'];

if(isset($_SESSION['username'])){
    $view = new View('theme/default.html','theme/nav/util.php','theme/sidebar.php',$blog['name'],"首頁");
    $view->addScript("<script>ts('.ts.dropdown:not(.basic)').dropdown();</script>");
} else {
    $view = new View('theme/default.html','theme/nav/default.html','theme/sidebar.php',$blog['name'],"首頁");
}

if (isset($_GET['ok'])){
    if ($_GET['ok'] == "login"){ ?>
<div class="ts inverted positive message">
    <p>早安!我的朋友!</p>
</div>
<?php } else if ($_GET['ok'] == "reg") { ?>
<div class="ts inverted primary message">
    <p>註冊成功</p>
</div>
<?php } else if ($_GET['ok'] == "logout") { ?>
<div class="ts inverted info message">
    <p>已登出</p>
</div>
<?php }
}
if (isset($_GET['err'])) {
    if ($_GET['err'] == "login"){ ?>
<div class="ts inverted negative message">
    <p>帳號或密碼錯誤</p>
</div>
<?php } else if ($_GET['err'] == "post") { ?>
<div class="ts negative message">
    <p>找不到文章</p>
</div>
<?php } else if ($_GET['err'] == "nologin") { ?>
<div class="ts warning message">
    <p>請先登入</p>
</div>
<?php }
}

if ($post_list['num_rows']>0){
    do {
        $name = getResult("SELECT `name` FROM `user` WHERE `username`='%s'",array($post_list['row']['username']))['row']['name'];
        $pid = $post_list['row']['pid'];
        $title = $post_list['row']['title'];
        $content = $post_list['row']['content'];
        $time = $post_list['row']['time'];
        $likes = getResult("SELECT * FROM `like` WHERE `pid`='%d'",array($pid));
        $islike = false;
        if ($likes['num_rows']>0 && isset($_SESSION['username'])){
            do {
                if($_SESSION['username'] == $likes['row']['username']){
                    $islike = true;
                }
            }while($likes['row'] = $likes['query']->fetch_assoc());
        }
        if ($likes['num_rows']<0){
            $likes = 0;
        } else {
            $likes = $likes['num_rows'];
        }
?>
<div class="ts card" data-id="<?php echo $pid; ?>">
    <div class="content">
        <div class="actions">
            <button class="ts secondary icon button" id="like" data-id="<?php echo $pid; ?>">
                <i class="thumbs <?php if(!$islike){echo "outline";}?> up icon"></i> <?php echo $likes; ?>
            </button>
            <a class="ts secondary icon button" href="post.php?pid=<?php echo $pid; ?>">
                Read <i class="right arrow icon"></i>
            </a>
        </div>
        <div class="header"><?php echo $title; ?></div>
        <div class="description" id="markdown">
            <?php echo sumarize($content,5); ?>
        </div>
    </div>
    <div class="extra content">
        <i class="user icon"></i> <?php echo $name;?> <i class="calendar icon"></i> <?php echo date('Y-m-d',strtotime($time)); ?>
    </div>
</div>
<?php }while($post_list['row'] = $post_list['query']->fetch_assoc());
    echo pages(@$_GET['page'],$all_post,$blog['limit']);
} else { ?>
<div class="ts info inverted message">
    <p>沒有文章，趕快去新增一個吧!</p>
</div>
<?php }
    $view->render();
?>