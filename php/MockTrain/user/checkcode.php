<?php
require_once('config.php');

if(!isset($_SESSION)){
  session_start();
}
if((!empty($_SESSION['check_word'])) && (!empty($_POST['checkword']))){
  if($_SESSION['check_word'] == $_POST['checkword']){
    $_SESSION['check_word'] = '';
    $index_1 = array(
      'Taipei' => 3, 'Taoyuan' => 4, 'Hsinchu' => 5,
      'Taichung' => 6, 'Tainan' => 7, 'Kaohsiung' => 8,
    );
    $index_2 = array(
      'Taipei' => 8, 'Taoyuan' => 7, 'Hsinchu' => 6,
      'Taichung' => 5, 'Tainan' => 4, 'Kaohsiung' => 3,
    );
    $db_train = new Db_train();
    $sth = $db_train->dbh->prepare("SELECT * from tb1 where type=(?)");
    $sth->execute(array($_POST['train']));

    $pt = $_POST['hour'][0].$_POST['hour'][1].$_POST['minute'][0].$_POST['minute'][1];
    $pt = intval($pt);
    echo '起點：'.$_POST['start'].'&nbsp;&nbsp;'.'終點：'.$_POST['dest'].'&nbsp;&nbsp;'.'車種：'.$_POST['train'].'<br>';
    $_SESSION['start'] = $_POST['start'];
    $_SESSION['dest'] = $_POST['dest'];
    $_SESSION['adult'] = $_POST['adult'];
    $_SESSION['child'] = $_POST['child'];
    $_SESSION['older'] = $_POST['older'];
    if($index_1[$_POST['start']] < $index_1[$_POST['dest']]){
      $_SESSION['direct'] = 1;
    }else{
      $_SESSION['direct'] = 0;
    }


?><form action="ticket_get.php" method="POST">
  <table border="1"><tr><td><b>車次</b></td><td><b>發車</b></td><td><b>到達</b></td><td><b>選擇</b></td></tr>
<?php
    while($row = $sth->fetch()){
      for($i=2; $i<9; $i++){
        if($row[$i][0]==9){
          $row[$i][0] = 0;
        }
      }
      if($_SESSION['direct']==1){
        if($pt <= intval($row[$index_1[$_POST['start']]]) &&
        $_POST['adult']+$_POST['child']+$_POST['older'] <= $row[9] &&
        $row[10]==$_SESSION['direct']){
          echo "<tr><td>";
          echo $row[0];
          echo "</td><td>";
          echo $row[$index_1[$_POST['start']]][0].$row[$index_1[$_POST['start']]][1].':'.
          $row[$index_1[$_POST['start']]][2].$row[$index_1[$_POST['start']]][3];
          echo "</td><td>";
          echo $row[$index_1[$_POST['dest']]][0].$row[$index_1[$_POST['dest']]][1].':'.
          $row[$index_1[$_POST['dest']]][2].$row[$index_1[$_POST['dest']]][3];
          echo "</td><td>";
          echo "<input type='checkbox' name='car' value=$row[0]>";
          echo "</td></tr>";
        }
      }else{
        if($pt <= intval($row[$index_2[$_POST['start']]]) &&
        $_POST['adult']+$_POST['child']+$_POST['older'] <= $row[9] &&
        $row[10]==$_SESSION['direct']){
          echo "<tr><td>";
          echo $row[0];
          echo "</td><td>";
          echo $row[$index_2[$_POST['start']]][0].$row[$index_2[$_POST['start']]][1].':'.
          $row[$index_2[$_POST['start']]][2].$row[$index_2[$_POST['start']]][3];
          echo "</td><td>";
          echo $row[$index_2[$_POST['dest']]][0].$row[$index_2[$_POST['dest']]][1].':'.
          $row[$index_2[$_POST['dest']]][2].$row[$index_2[$_POST['dest']]][3];
          echo "</td><td>";
          echo "<input type='checkbox' name='car' value=$row[0]>";
          echo "</td></tr>";
        }
      }
    }
?></table><input type="submit" name="action" value="book"></form>
<?php
    echo '<a href="./timetable.php">輸入正確(按此可返回)</a>';
  }else{
    echo '<a href="./timetable.php">Error輸入錯誤，將於一秒後跳轉(按此也可返回)</a>';
    echo '<meta http-equiv="refresh" content="2; url=./timetable.php">';
  }
}
?>
