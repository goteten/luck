<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ラック</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

  <p><h1>ラック</h1></p>

  <p><a href="index.php">TOPへ</a></p>


<?php
  require_once("functions.php");
  login_check(0);
?>

    <p><h2>ログインフォーム</h2></p>

    <form method="POST" action="sign-in-result.php">
  
      
      <div>
        ユーザーID（半角英数_）<br>
        <input type="text" name="id" placeholder="ここに書き込む"><br>
        パスワード（半角英数_）<br>
        <input type="password" name="password" placeholder="ここに書き込む"><br>
      </div>
  
  
      <div>
        <input type="submit" name="submit" value="送信">
      </div>
  
  
    </form>





  </body>


</html>