<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ラック</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

  <p><h1>ラック</h1></p>


<?php
  require_once("functions.php");
  login_check(0);

  if($_COOKIE["login_id"]==""){
    echo '<p><a href="sign-up.php"><h3>新規登録</h3></a></p>';
    echo '<p><a href="sign-in.php"><h3>ログイン</h3></a></p>';
  }

?>
  

  <br>
  <p><a href="luck.php"><h1>ラック測定</h1></a></p>


  <p><a href="record.php"><h3>自分/ユーザー全体の記録</h3></a></p>



<?php
  if(!($_COOKIE["login_id"]=="")){
    echo '<p><a href="sign-out.php"><h3>ログアウト</h3></a></p>';
  }
?>


  </body>


</html>