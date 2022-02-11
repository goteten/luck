<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ラック</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>


<?php

require_once("functions.php");

$id = htmlspecialchars($_POST["id"]);
$name = htmlspecialchars($_POST["username"]);
$pasword = htmlspecialchars($_POST["password"]);

if(is_alnum($id)&&is_alnum($pasword)){
  
}else{
  printf("IDまたはパスワードに半角英数_以外が含まれています！");
  exit;
}

if(file_exists("user/".$id."/password.txt")){
  printf("すでに存在するユーザーIDです！");
  exit;
}else{
  
}

if(preg_match("/^[0-9]+$/", $pasword)){
  printf("パスワードに半角英字も加えてください！");
  exit;
}
else if(preg_match("/^[a-zA-Z]+$/", $pasword)){
  printf("パスワードに半角数字も加えてください！");
  exit;
}else{
  
}


require_once("key.php");
$p_key = p_key_get();

$iv_value = openssl_random_pseudo_bytes(16);
$pasword_enc = openssl_encrypt($pasword, 'AES-256-CBC', $p_key, 0, $iv_value);


if(mkdir("user/".$id,0644,TRUE)){
  printf("登録");

  $f=fopen("user/".$id."/username.txt","w");
  fwrite($f,"".$name);
  fclose($f);
  chmod("user/".$id."/username.txt",0644);

  $f=fopen("user/".$id."/password.txt","w");
  fwrite($f,"".$pasword_enc);
  fclose($f);
  chmod("user/".$id."/password.txt",0644);

  //データ格納用ファイル
  $filename="user/".$id."/data";
  $all_data_array = array(
    'iv' => $iv_value,
  );
  file_put_contents($filename, serialize($all_data_array), LOCK_EX);

  //クッキーの設置
  setcookie("login_id",$id,time()+60*60*72,'/');
  setcookie("login_password",$pasword,time()+60*60*24,'/');

  header("Location: index.php");
  exit;

}else{
  printf("登録に失敗しました");
}


?>


  </body>


</html>