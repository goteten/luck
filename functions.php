
<?php

//複号用のキーは p_key_get() で取得
require_once("key.php");
$p_key = p_key_get();

//配列ファイル読み込み
function read_array($read_array_file_path,&$read_array_array){
  if(file_exists($read_array_file_path)){
    $read_array_array = unserialize(file_get_contents($read_array_file_path));
  }
  else{
    $read_array_array = array();
  }
}

//配列ファイル保存（どこに,何を）
function save_array($save_array_file_path,$save_array_array){
  file_put_contents($save_array_file_path, serialize($save_array_array), LOCK_EX);
}


//英数_のみか
function is_alnum($text) {
  return preg_match("/^[a-zA-Z0-9_]+$/",$text);
}

//ID→ユーザーネーム
function get_username($get_username_id){

  $get_username_id = htmlspecialchars($get_username_id);

  $f=fopen("user/".$get_username_id."/username.txt","r");
  return fgets($f);

}

//スタミナ消費
function stamina_consumption($val){
  $id=htmlspecialchars($_COOKIE["login_id"]);
  read_array("user/".$id."/data",$all_data);
  $all_data["stamina"]-=$val;
  save_array("user/".$id."/data",$all_data);
}

//スタミナ取得
function stamina_get(){
  $id=htmlspecialchars($_COOKIE["login_id"]);
  read_array("user/".$id."/data",$all_datas);
  return $all_datas["stamina"];
}

//アイコン表示
function view_icon($id,$size){
  $uploaded_path = 'user/'.$id.'/icon';
  if(file_exists($uploaded_path)){
    echo '<img src="'.$uploaded_path.'" alt="icon" width=" '.$size.' " height=" '.$size.' ">';
  }
  else{
    echo '<img src="pic/noicon.png" alt="icon" width=" '.$size.' " height=" '.$size.' ">';
  }
}


//ログインチェック - ユーザー情報の表示
function login_check($mode){
  //注意
  //スタミナの表記を補正するとき（実際の表記と1ずれてしまう不具合の解消）に $mode=100 が渡される
  //$mode=2 のときはユーザーページの表示

  if($_COOKIE["login_id"]==""){
    printf("<p>現在ログインしていません！</p>");

    if(isset($mode)){
      
      if($mode==1){
        exit;
      }
      if($mode==2){
        exit;
      }
    }


  }else{

    /*
    $id = $_COOKIE["login_id"];
    $pasword = $_COOKIE["login_password"];

    $f = fopen("user/".$id."/password.txt","r");
    $pasw_ = file_get_contents("user/".$id."/password.txt");

    $pasw = openssl_encrypt($pasword, 'AES-256-CBC', $p_key);*/

    $p_key = p_key_get();

    $id=htmlspecialchars($_COOKIE["login_id"]);
    $pasword=htmlspecialchars($_COOKIE["login_password"]);

    $f=fopen("user/".$id."/password.txt","r");
    $pasw = fgets($f);

    //基本データ読み込み
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
      
    }
    else{
      setcookie("login_id","",time()+60*60*24,'/');
      setcookie("login_password","",time()+60*60*24,'/');

      header("Location: index.php");
      exit;
    }

    //アイコン画像表示
    if($mode!=2){
      echo ' <br>';
      view_icon($id,60);

      //userページリンク
      if($mode<=1){
        printf('<a href="user.php">'.get_username($id).'様</a> ログイン中');
      }
    }

    

    //スタミナ再生処理
    //最終ログイン日付保存
    $time_now=time();
    if($time_now - $all_data["login_date"] >= 60){
      $all_data["stamina"] += intdiv( $time_now - $all_data["login_date"] , 60 ) * 5;
      $all_data["login_date"] = $time_now - (($time_now - $all_data["login_date"]) % 60);
    }
    if($all_data["stamina"]>=100){
      $all_data["stamina"]=100;
    }
    

    //スタミナ読み込み
    
    $stamina=$all_data["stamina"];


    if($mode!=100){
      printf("<p>スタミナ残量:".$stamina." （1分で5回復）</p>");
    }
    else{
      printf("<p>スタミナ残量:".max($stamina-1,0)." （1分で5回復）</p>");
    }

    //クッキー
    setcookie("login_id",$id,time()+60*60*72,'/');
    setcookie("login_password",$pasword,time()+60*60*72,'/');


    //記録
    save_array("user/".$id."/data",$all_data);
    
  }

}

?>