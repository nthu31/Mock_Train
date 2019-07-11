<?php
require_once('config.php');
if(!isset($_SESSION)){
  session_start();
}
$index_1 = array('Taipei' => 3, 'Taoyuan' => 4, 'Hsinchu' => 5, 'Taichung' => 6, 'Tainan' => 7, 'Kaohsiung' => 8,);
$index_2 = array('Taipei' => 8, 'Taoyuan' => 7, 'Hsinchu' => 6, 'Taichung' => 5, 'Tainan' => 4, 'Kaohsiung' => 3,);
$db_train = new Db_train();
$db_seat = new Db_seat();
$db_book = new Db_book();

$total_ticket = $_SESSION['adult']+$_SESSION['child']+$_SESSION['older'];

$sth_train = $db_train->dbh->prepare("SELECT * from tb1 where id=(?)");
$sth_train->execute(array($_POST['car']));
$row = $sth_train->fetch();
if($row['seat'] < $total_ticket){
  echo "Ticket is out of stock.<br>";
  echo '<a href="./timetable.php">按此可返回</a>';
  //echo '<meta http-equiv="refresh" content="2; url=./timetable.php">';
}else{
  echo $row[1].'    '.$row[0].'次<br>';
  echo $_SESSION['start'].'&nbsp;&nbsp;---->&nbsp;&nbsp;'.$_SESSION['dest'].'<br>';
  if($_SESSION['direct']==1){
    echo $row[$index_1[$_SESSION['start']]][0].$row[$index_1[$_SESSION['start']]][1].':'.
    $row[$index_1[$_SESSION['start']]][2].$row[$index_1[$_SESSION['start']]][3].'開&nbsp;&nbsp;'.
    $row[$index_1[$_SESSION['dest']]][0].$row[$index_1[$_SESSION['dest']]][1].':'.
    $row[$index_1[$_SESSION['dest']]][2].$row[$index_1[$_SESSION['dest']]][3].'到<br>';
  }else{
    echo $row[$index_2[$_SESSION['start']]].'開&nbsp;&nbsp;'.$row[$index_2[$_SESSION['dest']]].'到<br>';
  }
  echo "Total Price: 100<br>";

  //------train------
  $sth_train = $db_train->dbh->prepare("UPDATE tb1 set seat=(?) where id=(?)");
  $sth_train->execute(array(
    $row[9]-($_SESSION['adult']+$_SESSION['child']+$_SESSION['older']),
    $row[0]));

  //------seat-------
  $table = 't'.$row[0];
  $sth_seat = $db_seat->dbh->prepare("SELECT * from ".$table);
  $sth_seat->execute();

  $total_ticket = $_SESSION['adult']+$_SESSION['child']+$_SESSION['older'];
  $record_seat = [];
  $count = 0;
  $seats = $sth_seat->fetch();
  for($i=0; $i<sizeof($seats); $i++){
    if($seats[$i]==0){
      $r = floor(($i+1)/10);
      $m = ($i+1)%10;
      switch ($r){
        case 0:
          $name = 'A0'.$m; break;
        case 1:
          if($m==0){
            $name = 'A10'; break;
          }else{
            $name = 'B0'.$m; break;
          }
        case 2:
          if($m==0){
            $name = 'B10'; break;
          }else{
            $name = 'C0'.$m; break;
          }
        case 3:
          $name = 'C10'; break;
      }
      array_push($record_seat, $name);
      $count++;
    }
    if($count==$total_ticket){
      break;
    }
  }
  for($i=0; $i<sizeof($record_seat); $i++){
    $name = $record_seat[$i];
    $sth_seat = $db_seat->dbh->prepare("UPDATE ".$table." set ".$name."=1");
    $sth_seat->execute();
  }
  echo "座位：";
  for($i=0; $i<sizeof($record_seat); $i++){
    echo $record_seat[$i].'&nbsp;';
  }
  echo '<br><a href="./timetable.php">訂票成功，按此回主頁</a>';

  //---------book------------
  $sth_book = $db_book->dbh->prepare("SELECT * FROM book ORDER BY id DESC LIMIT 1");
  $sth_book->execute();
  $sss = '';
  for($i=0; $i<sizeof($record_seat); $i++){
    $sss = $sss.$record_seat[$i];
  }
  if($select = $sth_book->fetch()){
    $sth_book = $db_book->dbh->prepare("INSERT into book(id,user,car,start,dest,day,adult,
      child,old,paid,seats) values(?,?,?,?,?,?,?,?,?,?,?)");
    $sth_book->execute(array($select[0]+1, $_SESSION['user'],$_POST['car'],$_SESSION['start'],$_SESSION['dest'],0,
    $_SESSION['adult'],$_SESSION['child'],$_SESSION['older'],0,$sss));
  }else{
    $sth_book = $db_book->dbh->prepare("INSERT into book(id,user,car,start,dest,day,adult,
      child,old,paid,seats) values(?,?,?,?,?,?,?,?,?,?,?)");
    $sth_book->execute(array(1, $_SESSION['user'],$_POST['car'],$_SESSION['start'],$_SESSION['dest'],0,
    $_SESSION['adult'],$_SESSION['child'],$_SESSION['older'],0,$sss));
  }
}
?>
