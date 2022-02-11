<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ラック</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>


<?php

require_once("key.php");
$p_key = p_key_get();

$id=htmlspecialchars($_POST["id"]);
$pasword=htmlspecialchars($_POST["password"]);

$f=fopen("user/".$id."/password.txt","r");

if($f==false){
  printf("そのidのアカウントは存在しません！");
}
else{
  $pasw = fgets($f);

  $pasword_enc = openssl_encrypt($pasword, 'AES-256-CBC', $p_key);

  if($pasword_enc==$pasw){
    setcookie("login_id",$id,time()+60*60*24,'/');
    setcookie("login_password",$pasword,time()+60*60*24,'/');
  
    header("Location: index.php");
    exit;
  
  }else{
    printf("パスワードまたはidが間違っています！");
  }
}



?>


  </body>


</html>