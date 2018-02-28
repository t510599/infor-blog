<?php
require_once('connection/SQL.php');
require_once('config.php');

if(!isset($_SESSION['username'])){ ?>
    <div class="ts basic center aligned segment">登入或是<a href="account.php?new">註冊</a></div>
<?php} else {
    $name = getResult("SELECT `name` FROM `user` WHERE `username`=`%s`",array($_SESSION['username']));
    $name = $name['row']['name'];
    ?>
    <h3 class="ts header">
        <img class="ts circular image" src="https://tocas-ui.com/assets/img/5e5e3a6.png" style="margin-right:5px;"> <?php echo $name;?>
    </h3>
<?php}?>

<div class="ts fluid action input" style="margin-bottom:10px;">
    <input type="text" placeholder="在這搜尋人、事、物">
    <button class="ts button">搜尋</button>
</div>
<div class="ts segments" style="margin-bottom:10px;">
    <div class="ts tertiary center aligned segment">名稱</div>
    <div class="ts segment">
        <p>項目</p>
        <p>項目</p>
        <p>項目</p>
    </div>
</div>
<div class="ts segments" style="margin-bottom:10px;">
    <div class="ts tertiary center aligned segment">名稱</div>
    <div class="ts segment">
        <p>項目</p>
        <p>項目</p>
        <p>項目</p>
    </div>
</div>