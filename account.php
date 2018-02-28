<?php
// create ?>
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
//edit ?>
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
