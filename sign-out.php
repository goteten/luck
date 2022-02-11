<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>制限</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>


<?php

setcookie("login_id","",time()+60,'/');
header("Location: index.php");
exit;

?>


  </body>


</html>