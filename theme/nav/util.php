<?php
$self = @end(explode('/',$_SERVER['PHP_SELF']));
?>
<div class="ts top attached pointing secondary large menu">
    <div class="ts narrow container">
        <a href="index.php" class="<?php if($self == 'index.php'){echo "active ";} ?>item">首頁</a>
        <div class="ts <?php if($self == 'post.php'){echo "active ";} ?>dropdown item">
            <div class="text">文章</div>
            <div class="menu">
                <a href="post.php?new" class="item">新增</a>
                <a href="post.php" class="item">列表</a>
            </div>
        </div>
        <a href="account.php" class="<?php if($self == 'account.php'){echo "active ";} ?>item">帳號</a>
        <a href="logout.php" class="right item">登出</a>
    </div>
</div>