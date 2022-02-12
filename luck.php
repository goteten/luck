<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ラック</title>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

  <p><h1>ラック</h1></p>

  <a href="index.php">TOP</a>


<?php
  require_once("functions.php");
  if(isset($_GET["data"])){
    login_check(100);
  }
  else{
    login_check(1);
  }
  
?>

<script src="js/main.js"></script>

<?php
  if(isset($_GET["data"])&&stamina_get()<=0){
    //スタミナがない！
    echo "スタミナが不足しています！<br>";
  }
  else if(isset($_GET["data"])){
    //モード0
    if($_GET["data"]==0){
      //スタミナ消費
      stamina_consumption(1);


      echo '<br>結果<br>';
      echo '<input type="button" name="b_" value="再試行" onclick="OnButtonClick(0);"><br>';

      $count = array();
      $dice_count_max=array();
      $sum=0;

      //出目カウンターリセット
      $dice_count = array();
      for($j=1;$j<=6;$j++){
        $dice_count[$j]=0;
      }

      //$errは乱数を信用していないための誤差項
      $err=time()%17;
      //サイコロを10回振る
      for($iter=0;$iter<10;$iter++){
        $err+=2*$err;
        $err%=17;

        $mrand=(mt_rand(1,6)+$err)%6+1;
        $dice_count[$mrand]++;
        $sum+=$mrand;

        echo '<img src="pic/'.$mrand.'.png" alt="'.$mrand.'">';
      }

      //何が何個出たか記録
      for($j=1;$j<=6;$j++){
        if($dice_count_max[$j]<$dice_count[$j]){
          $dice_count_max[$j]=$dice_count[$j];
        }
        $count[$j."x".$dice_count[$j]]++;
      }

      //記録の読み込み
      read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );
      echo "<br><br>出目の合計:".$sum."<br>";
      echo "出目の合計自己ベスト:".$user_record["0-sum"];


      $tweet_text = 'サイコロを10個振る 記録: '.$sum.'';
      if($user_record["0-sum"]<$sum){
        echo '<br>記録更新！';
        $tweet_text = $tweet_text.' 記録更新！';
      }

      echo '<br><a href="https://twitter.com/intent/tweet?text='.$tweet_text.' http://sei-gen.lsv.jp/luck/index.php" target="_blank">ツイートする</a><br>';



////////////////////////////////////////////////////////////////////////////////////////
      //個人記録
      //記録の読み込み
      read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );

      //バージョンアップ
      if($user_record["0-sum"]>1000){
        $ver_up_mode=1;
        $user_record["0-sum"]=0;
      }




      //出目の合計最高記録保存 (0-sum)
      $user_record["0-sum"] = max( $user_record["0-sum"] , $sum );
      //出目の累計 (0-total_jxk)
      for($j=1;$j<=6;$j++){
        for($k=1;$k<=10;$k++){
          if($ver_up_mode==1){
            $user_record["0-total_".$j."x".$k]=0;
          }
          $user_record["0-total_".$j."x".$k]+=$count[$j."x".$k];
        }
        echo "<br>";
      }

      //何回遊んだか（0-play）
      if($ver_up_mode==1){
        $user_record["0-play"]=0;
      }
      $user_record["0-play"]++;

      //記録の保存
      save_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );





      //ユーザー全体の記録
      //記録の読み込み
      read_array( "record/record_0" , $all_user_record );

      //最高出目合計記録（0-total-xyz） id:日付
      //50以上で保存
      if($sum>=50){
        $all_user_record["0-total-".$sum][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
      }
      //10以下で保存
      if($sum<=10){
        $all_user_record["0-total-".$sum][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
      }

      //x8,x9,x10 （0-?x?_?）（何x何が何回）
      for($i=1;$i<=6;$i++){
        for($j=8;$j<=10;$j++){

          if($count[$i."x".$j]>=1){
            $first_key = "0-".$i."x".$j."_".$count[$i."x".$j];

            $all_user_record[$first_key][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
          }

        }
      }


      //記録の保存
      save_array( "record/record_0" , $all_user_record );
/////////////////////////////////////////////////////////////////////////////////////////

    }
    //モード1
    if($_GET["data"]==1){
      //スタミナ消費
      stamina_consumption(1);


      echo '<br>結果（※100000文字が上限）<br>';
      echo '<input type="button" name="b_" value="再試行" onclick="OnButtonClick(1);">';


      //記録はここに保存
      $record_in_mode1=0;



      echo '<p><b>';

      //実際に構成
      //魑魅魍魎
      $chimi[0]="魑";
      $chimi[1]="魅";
      $chimi[2]="魍";
      $chimi[3]="魎";

      //3つ前まで覚えておく
      //$prev_in_mode_1[何個前]
      $prev_in_mode_1[0]="";
      $prev_in_mode_1[1]="";
      $prev_in_mode_1[2]="";
      $prev_in_mode_1[3]="";

      for($count_1=1;$count_1<=100000;$count_1++){

        $rand_value = mt_rand(0,3);
        $prev_in_mode_1[0] = $chimi[$rand_value];


        //表示部
        if($count_1%50==1&&$count_1>=2){
          echo "<br>";
        }

        if($prev_in_mode_1[0]==$chimi[0]){
          echo '<font color="#AA0000">';
        }
        if($prev_in_mode_1[0]==$chimi[1]){
          echo '<font color="#006600">';
        }
        if($prev_in_mode_1[0]==$chimi[2]){
          echo '<font color="#0000AA">';
        }
        if($prev_in_mode_1[0]==$chimi[3]){
          echo '<font color="#AA00AA">';
        }

        echo $prev_in_mode_1[0];
        echo '</font>';
        

        

        if($prev_in_mode_1[3].$prev_in_mode_1[2].$prev_in_mode_1[1].$prev_in_mode_1[0]=="魑魅魍魎"){
          $record_in_mode1 = $count_1;
          break;
        }

        for($i=3;$i>=1;$i--){
          $prev_in_mode_1[$i] = $prev_in_mode_1[$i-1];
        }

        
      }
      echo '</b></p>';

      //記録の読み込み
      read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );


      echo '記録: '.$count_1.'文字<br>';
      echo '自己ベスト: '.$user_record["1-long"];

  
      $tweet_text = '魑魅魍魎が完成するまで 記録: '.$count_1.'文字';
      if($user_record["1-long"]<$count_1){
        echo '<br>記録更新！';
        $tweet_text = $tweet_text.' 記録更新！';
      }

      echo '<br><a href="https://twitter.com/intent/tweet?text='.$tweet_text.' http://sei-gen.lsv.jp/luck/index.php" target="_blank">ツイートする</a><br>';


////////////////////////////////////////////////////////////////////////////////////////
      //個人記録
      //記録の読み込み
      //read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );

      //最長記録保存 (1-long)
      $user_record["1-long"] = max( $user_record["1-long"] , $count_1 );

      //何回遊んだか（1-play）
      $user_record["1-play"]++;

      //記録の保存
      save_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );





      //ユーザー全体の記録
      //記録の読み込み
      read_array( "record/record_1" , $all_user_record );

      

      //最高記録（1-total-$count_1） id:日付
      //1000以上で保存

      if($count_1>=1000){
        $all_user_record["1-total-".$count_1][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
      }
      


      //記録の保存
      save_array( "record/record_1" , $all_user_record );
/////////////////////////////////////////////////////////////////////////////////////////


    }

    //モード2
    if($_GET["data"]==2){
      //スタミナ消費
      stamina_consumption(1);



      echo '<br>結果<br>';
      echo '<input type="button" name="b_" value="再試行" onclick="OnButtonClick(2);">';


      //記録はここに保存
      $record_in_mode2=0;



      //実際の計算
      $coordinate_A = 5;
      $coordinate_B = 5;


      $box_field = array();

      $box_field[$coordinate_A][$coordinate_B] = "01";

      $count_2=1;


      while(true){


        $rand_value_in_mode2 = mt_rand(0,3);

        if( $rand_value_in_mode2 == 0 ){
          $coordinate_A++;
        }
        if( $rand_value_in_mode2 == 1 ){
          $coordinate_A--;
        }
        if( $rand_value_in_mode2 == 2 ){
          $coordinate_B++;
        }
        if( $rand_value_in_mode2 == 3 ){
          $coordinate_B--;
        }



        if(isset($box_field[$coordinate_A][$coordinate_B])){

          $count_2++;

          $box_field[$coordinate_A][$coordinate_B] = "";

          if($count_2<10){
            $box_field[$coordinate_A][$coordinate_B] = "0";
          }
          
          $box_field[$coordinate_A][$coordinate_B] = $box_field[$coordinate_A][$coordinate_B].$count_2;

          break;
        }

        if($coordinate_A <= 0){
          $count_2++;
          break;
        }
        if($coordinate_B <= 0){
          $count_2++;
          break;
        }
        if($coordinate_A > 9){
          $count_2++;
          break;
        }
        if($coordinate_B > 9){
          $count_2++;
          break;
        }


        $count_2++;

        $box_field[$coordinate_A][$coordinate_B] = "";

        if($count_2<10){
          $box_field[$coordinate_A][$coordinate_B] = "0";
        }
        
        $box_field[$coordinate_A][$coordinate_B] = $box_field[$coordinate_A][$coordinate_B].$count_2;




      }


      echo '<br><p>';


      for($i=1;$i<=9;$i++){

        for($j=1;$j<=9;$j++){


          if(isset($box_field[$i][$j])){
            //echo $box_field[$i][$j];
            echo '<img src="pic/snake/'.substr($box_field[$i][$j],0,1).'.png" alt="'.substr($box_field[$i][$j],0,1).'">';
            echo '<img src="pic/snake/'.substr($box_field[$i][$j],1,1).'.png" alt="'.substr($box_field[$i][$j],1,1).'">';
          }
          else{
            echo '<img src="pic/snake/_.png" alt="__">';

          }
          echo ' ';


        }

        echo '<br>';

      }


      echo '</p>';

      $count_2--;


      //記録の読み込み
      read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );


      echo '記録: '.$count_2.'<br>';
      echo '自己ベスト: '.$user_record["2-max_grid"];

      $tweet_text = '9×9をランダムウォーク 記録: '.$count_2.'';
      if($user_record["2-max_grid"]<$count_2){
        echo '<br>記録更新！';
        $tweet_text = $tweet_text.' 記録更新！';
      }

      echo '<br><a href="https://twitter.com/intent/tweet?text='.$tweet_text.' http://sei-gen.lsv.jp/luck/index.php" target="_blank">ツイートする</a><br>';





      
      echo '<p>記録されるもの: 9×9のマス目を黒で塗りつぶした面積<br></p>';

      echo '<p>＜ルール＞<br>';
      echo '上下左右に広がる9×9のマス目があります。これらは最初白に塗られています。<br>';
      echo '(A,B)=(5,5)を初期状態として、次のような操作を行います。<br></p>';
      echo '<p>1. (A,B)のマスを黒く塗る。</p>';
      echo '<p>2. Aの値に1か-1を加えるか、Bの値に1か-1を加える。（それぞれ1/4の確率で行われる。）</p>';
      echo '<p>上の1,2を既に黒く塗られていたマスに移動するか、<br>';
      echo 'A≦0,B≦0,A＞9,B＞9となるまで繰り返します。</p>';
      echo '<p>実際の表示には黒いマスに塗りつぶされた順番が表示されています。</p>';





////////////////////////////////////////////////////////////////////////////////////////
      //個人記録
      //記録の読み込み
      //read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );

      //最長記録保存 (2-max_grid)
      $user_record["2-max_grid"] = max( $user_record["2-max_grid"] , $count_2 );

      //何回遊んだか（2-play）
      $user_record["2-play"]++;

      //記録の保存
      save_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );





      //ユーザー全体の記録
      //記録の読み込み
      read_array( "record/record_2" , $all_user_record );

      

      //最高記録（2-max_grid-$count_2） id:日付
      //10以上で保存

      if($count_2>=10){
        $all_user_record["2-max_grid-".$count_2][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
      }
      


      //記録の保存
      save_array( "record/record_2" , $all_user_record );
/////////////////////////////////////////////////////////////////////////////////////////


    }
    





  }
  
  //モードが指定されていない場合
  //if(!isset($_GET["data"])){
  if(true){
    echo'
<br>
<p>モード選択</p>
<br>
    ';

    //echo '<input type="button" name="b'.$i.'" value="'.$odai[$i].'" onclick="OnButtonClick('.$i.')";
    $mode[0]="サイコロ10個";
    $mode[1]="「魑魅魍魎」が完成するまで";
    $mode[2]="9×9をランダムウォーク";

    for($i=0;$i<3;$i++){
      echo '<input type="button" name="b'.$i.'" value="'.$mode[$i].'" onclick="OnButtonClick('.$i.');">';
      echo '<br>';
    }

    echo '<form method="GET" action="luck.php" name="get_action">
<input type="hidden" name="data" value="">
</form>';

  }
?>





  </body>


</html>
