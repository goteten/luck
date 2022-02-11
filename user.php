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
  login_check(2);

  
  /*
   echo "<pre>";
    var_dump($_FILES);
   echo "</pre>";
   */
 



  $id=htmlspecialchars($_COOKIE["login_id"]);

  
  //画像の取得（アイコン変更時）
  if(!empty($_FILES)){

    
    if($_FILES['picture']['size']>1024*1024){
      //サイズが大きすぎる場合
      echo 'ファイルサイズが大きすぎます！<br>';
    }
    else{

      $filename = $_FILES['picture']['name'];
      $uploaded_path = 'user/'.$id.'/icon';

      if(file_exists($uploaded_path)){
        unlink($uploaded_path);
      }

      $result = @move_uploaded_file($_FILES['picture']['tmp_name'],$uploaded_path);

    }
    
  }

  $uploaded_path = 'user/'.$id.'/icon';
  if(file_exists($uploaded_path)){
    echo ' <img src="'.$uploaded_path.'" alt="icon" width="300" height="300"><br>';
  }
  else{
    echo ' <img src="pic/noicon.png" alt="icon" width="300" height="300"><br>';
  }
  echo ''.get_username($id).'様のページ<br>';



?>

    <p><h2>アイコンを変更(png/jpegで1MB以下 画像は正方形で表示されます)</h2></p>


    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="picture" accept=".png, .jpg, .jpeg">
      <button type="submit">送信する</button>
    </form>

  </body>


</html>