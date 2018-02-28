<div class="ts top attached pointing secondary large menu">
    <div class="ts narrow container">
        <a href="index.php" class="item">首頁</a>
        <div class="ts dropdown <?php if($_SERVER['PHP_SELF'] == '/post.php'){echo "active";} ?> item">
            <div class="text">文章</div>
            <div class="menu">
                <a href="post.php?new" class="item">新增</a>
                <a href="post.php" class="item">列表</a>
            </div>
        </div>
        <a href="account.php" class="<?php if($_SERVER['PHP_SELF'] == '/account.php'){echo "active";} ?> item">帳號</a>
        <a href="logout.php" class="right item">登出</a>
    </div>
</div>