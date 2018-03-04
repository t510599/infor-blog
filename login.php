<?php
require_once('connection/SQL.php');
require_once('config.php');

if((isset($_POST['username']))&&(isset($_POST['password']))&&($_POST['username']!='')&&($_POST['password']!='')){
	if(login($_POST['username'],$_POST['password'])==1){
		header('Location: index.php?ok=login');
		exit;
	}else{
		header('Location: index.php?err=login');
		exit;
    }
} else { ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.css" rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.js"></script>
    <title>登入 | <?php echo $blog['name']; ?></title>
	<style type="text/css">
		html,body {
			height: 100%;
			margin: 0;
		}
		body {
			background: linear-gradient(180deg,deepskyblue 5%,aqua);
		}
        .segment {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="ts narrow container">
        <br>
        <br>
		<h1 class="ts center aligned header">
			<?php echo $blog['name']; ?>
			<div class="sub header">傳送門</div>
		</h1>
        <div class="ts centered secondary segment">
            <form class="ts form" method="POST" action="login.php">
                <div class="field">
                    <label>帳號</label>
                    <input type="text" name="username">
                </div>
                <div class="field">
                    <label>密碼</label>
                    <input type="password" name="password">
                </div>
                <input type="submit" class="ts positive fluid button" value="登入">
            </form>
        </div>
    </div>
</body>
</html>
<?php }
?>