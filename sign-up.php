<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>制限</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

  <p><h1>制限</h1></p>
  <p>限られた文字で生み出す価値観</p>

  <p><a href="index.php">TOPへ</a></p>

<?php
  require_once("functions.php");
  login_check(0);
?>

    <p><h2>フォーム</h2></p>

    <form method="POST" action="sign-up-result.php">
      
      
      <div>
        ユーザーID（半角英数_）<br>
        <input type="text" name="id" placeholder="ここに書き込む"><br>
        ユーザー名（主に表示されるのはこちら）<br>
        <input type="text" name="username" placeholder="ここに書き込む"><br>
        パスワード（半角英数_）※半角英字と半角数字の両方が少なくとも1つ含まれるものにしてください。<br>
        <input type="password" name="password" placeholder="ここに書き込む"><br>
      </div>
      
      
      <div>
        <input type="submit" name="submit" value="送信">
      </div>
  
  
    </form>





  </body>


</html>