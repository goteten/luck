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
  login_check(1);
?>

<script src="js/main.js"></script>



<h2>記録館</h2>

<?php
if(isset($_GET["data"])){
  echo'
<br>
<p>記録を見るモードの選択</p>
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
  echo '<br>';

  echo '<form method="GET" action="record.php" name="get_action">
<input type="hidden" name="data" value="">
</form>';

}
if(!isset($_GET["data"])){

  echo '<input type="button" name="b0" value="ユーザー全体の記録" onclick="OnButtonClick(0);">';
  echo '<form method="GET" action="record.php" name="get_action">
<input type="hidden" name="data" value="">
</form>';
  echo '<br>';






  read_array( "user/".$_COOKIE["login_id"]."/record" , $user_record );
  
  //サイコロ10個
  echo "<h3>サイコロ10個</h3>";

  if(!isset($user_record["0-play"])){
    $user_record["0-play"]=0;
  }
  echo "<p>試行回数:".$user_record["0-play"]."回</p>";

  echo "<p>出目の合計自己ベスト:".$user_record["0-sum"]."</p>";

  /*
  //出目の累計 (0-total_ixj)
  for($j=1;$j<=6;$j++){
    echo "<p>";
    for($k=1;$k<=10;$k++){
      if(!isset($user_record["0-total_".$j."x".$k])){
        $user_record["0-total_".$j."x".$k]=0;
      }
      echo "".$j."×".$k.":".$user_record["0-total_".$j."x".$k]."回 ";
    }
    echo "</p>";
  }
  */

  //記録の表示（表の作成）
  echo '何が何個出たかの記録<br>';
  echo '<table border="5">';
  echo '<tr>';
  echo '<th></th>';
  for($k=1;$k<=10;$k++){
    echo '<th>'.$k.'個</th>';
  }
  echo '</tr>';

  for($j=1;$j<=6;$j++){
    echo '<tr>';
    echo '<th>';
    echo '<img src="pic/'.$j.'.png" alt="'.$j.'">';
    echo '</th>';
    for($k=1;$k<=10;$k++){
      if(!isset($user_record["0-total_".$j."x".$k])){
        $user_record["0-total_".$j."x".$k]=0;
      }
      if($user_record["0-total_".$j."x".$k]>=1){
        echo "<td>".$user_record["0-total_".$j."x".$k]."回</td>";
      }
      else{
        echo '<td align="center">-</td>';
      }
      
    }
    echo '</tr>';
  }
  echo '</table>';


  //「魑魅魍魎」が完成するまで
  echo "<h3>「魑魅魍魎」が完成するまで</h3>";

  if(!isset($user_record["1-play"])){
    $user_record["1-play"]=0;
  }
  echo "<p>試行回数:".$user_record["1-play"]."回</p>";

  echo "<p>最長記録:".$user_record["1-long"]."</p>";


  //9×9をランダムウォーク
  echo "<h3>9×9をランダムウォーク</h3>";

  if(!isset($user_record["2-play"])){
    $user_record["2-play"]=0;
  }
  echo "<p>試行回数:".$user_record["2-play"]."回</p>";

  echo "<p>最高記録:".$user_record["2-max_grid"]."</p>";

}
else{
  //全体記録

  //サイコロ10個を100回振る

  /*
  //3600以上で保存
  if($sum>=3600){
    $all_user_record["0-total-".$sum][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
  }
  //3400以下で保存
  if($sum<=3400){
    $all_user_record["0-total-".$sum][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
  }
  */
  if($_GET["data"]==0){
    read_array( "record/record_0" , $all_user_record );



    //記録表示：大きい方
    $iter=0;

    echo "<h2>ユーザー全体の最高記録（※50以上で保存）</h2><br>";
    for($sum=100;$sum>=50;$sum--){

      if($iter>=100){
        break;
      }


      if(isset($all_user_record["0-total-".$sum])){
        $iter++;

        echo "<p>";
        
        foreach( $all_user_record["0-total-".$sum] as $user_id_of_record => $moment_of_achievement ){

          view_icon($user_id_of_record,20);
          echo "".$sum." by ".get_username($user_id_of_record)." ( ";
          
          foreach( $moment_of_achievement as $value_of_time => $times_of_achievement ){

            
            
            echo "".$value_of_time." ";
            

            
  
          }
          echo ") <br>";
        }
        echo "</p>";

      }




    }


    //記録表示 x8,x9,x10 達成
    /*
    //x8,x9,x10 （0-?x?_?）（何x何が何回）
      for($i=1;$i<=6;$i++){
        for($j=8;$j<=10;$j++){

          if($count[$i."x".$j]>=1){
            $first_key = "0-".$i."x".$j."_".$count[$i."x".$j];

            $all_user_record[$first_key][$_COOKIE["login_id"]][date('Y/m/d H:i:s',time())]++;
          }

        }
      }
    */

    echo "<h2>特殊な記録（※同じ出目を8個以上で保存）</h2><br>";

    for($i=1;$i<=6;$i++){
      for($j=8;$j<=10;$j++){
        for($k=1;$k<=1;$k++){
          // $i x $j が $k=1回
          $first_key = "0-".$i."x".$j."_".$k;




          if(isset($all_user_record[$first_key])){
      
            echo "<p>";
            
            foreach( $all_user_record[$first_key] as $user_id_of_record => $moment_of_achievement ){

              view_icon($user_id_of_record,20);
              echo "".$i."×".$j." by ".get_username($user_id_of_record)." ( ";
              
              foreach( $moment_of_achievement as $value_of_time => $times_of_achievement ){
      
                
                
                echo "".$value_of_time." ";
                
      
                
      
              }
              echo ") <br>";
            }
            echo "</p>";
      
          }




        }
      }
    }


    





    /////////////////////////////////////////////////////////////////
  }
  if($_GET["data"]==1){
    read_array( "record/record_1" , $all_user_record );



    //記録表示：大きい方 上位100
    $iter=0;

    echo "<h2>ユーザー全体の最高記録（※1500以上で表示/上位100記録のみ表示）</h2><br>";
    for($sum=100000;$sum>=1500;$sum--){

      if($iter>=100){
        break;
      }


      if(isset($all_user_record["1-total-".$sum])){
        $iter++;

        echo "<p>";
        
        foreach( $all_user_record["1-total-".$sum] as $user_id_of_record => $moment_of_achievement ){

          view_icon($user_id_of_record,20);
          echo "".$sum." by ".get_username($user_id_of_record)." ( ";
          
          foreach( $moment_of_achievement as $value_of_time => $times_of_achievement ){

            
            
            echo "".$value_of_time." ";
            

            
  
          }
          echo ") <br>";
        }
        echo "</p>";

      }




    }


    
    /////////////////////////////////////////////////////////////////
  }


  if($_GET["data"]==2){
    read_array( "record/record_2" , $all_user_record );



    //記録表示：大きい方 上位100
    $iter=0;

    echo "<h2>ユーザー全体の最高記録（※13以上で表示）</h2><br>";
    for($sum=100;$sum>=13;$sum--){

      if($iter>=100){
        break;
      }


      if(isset($all_user_record["2-max_grid-".$sum])){
        $iter++;

        echo "<p>";
        
        foreach( $all_user_record["2-max_grid-".$sum] as $user_id_of_record => $moment_of_achievement ){

          view_icon($user_id_of_record,20);
          echo "".$sum." by ".get_username($user_id_of_record)." ( ";
          
          foreach( $moment_of_achievement as $value_of_time => $times_of_achievement ){

            
            
            echo "".$value_of_time." ";
            

            
  
          }
          echo ") <br>";
        }
        echo "</p>";

      }




    }


    
    /////////////////////////////////////////////////////////////////
  }
}


?>



  </body>


</html>