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

  //開発の段階で初期ベクトルが設定されていなかったアカウントがある為、
  //ここで初期ベクトルの生成とパスワードの再暗号化をする
  read_array("user/".$id."/data",$all_data);

  $pasword_enc = openssl_encrypt($pasword, 'AES-256-CBC', $p_key, 0, $all_data['iv']);

  if($pasword_enc==$pasw){
    //開発の段階で初期ベクトルが設定されていなかったアカウントがある為、
    //ここで初期ベクトルの生成とパスワードの再暗号化をする
    if($all_data['iv']==""){
      $iv_value = openssl_random_pseudo_bytes(16);
      $all_data['iv']=$iv_value;
      $pasword_enc = openssl_encrypt($pasword, 'AES-256-CBC', $p_key, 0, $iv_value);

      $f=fopen("user/".$id."/password.txt","w");
      fwrite($f,"".$pasword_enc);
      fclose($f);
      chmod("user/".$id."/password.txt",0644);
    }
    save_array("user/".$id."/data",$all_data);
    

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
