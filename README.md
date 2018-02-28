# infor-blog

Infor 考幹 第二大題 blog **本專案僅做研究用途，不可用作正式系統**

1. 前端設計 (15%)
2. 使用帳號密碼驗證的登入系統 (35%)
3. 登入後可於前端新增、編輯、刪除貼文 (30%)
4. 可以幫任何貼文按讚，每貼文限一次 (20%)

## Used libraries

* jQuery
* TocasUI [https://tocas-ui.com](https://tocas-ui.com)
* Showdown [http://showdownjs.com/](http://showdownjs.com/)
* Markdown Editor [https://github.com/lepture/editor](https://github.com/lepture/editor)

## 參考
Secret Blog [https://blog.gdsecret.net](https://blog.gdsecret.net)  
有些架構是參考自secret blog，並使用了其中一部份的程式碼

## Installation

請設定以下檔案:  
`connection/SQL.php`  
```
$database_SQL = "";//資料庫名稱
$username_SQL = "";//連線帳號
$password_SQL = "";//連線密碼
$hostname_SQL = "";//MySQL伺服器
```
`config.php`  
```
$blog['name'] = 'Infor blog'; //網站名稱
$blog['limit']='10';//首頁顯示文章數量
```
將 `db.sql` 匯入資料庫後即可使用  
已有預設帳號:demo 密碼:demo
