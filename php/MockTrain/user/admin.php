<!DOCTYPE html>
<html>
<body>

<?php
session_start();
if (!isset($_SESSION['is_login'])){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}else if ($_SESSION['is_login']==0){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}else if ($_SESSION['is_login']==1){
  if ($_SESSION['user']!='admin'){
    header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
    exit;
  }
}
include "logpage.php";

$dbh = new PDO("sqlite:../db/user.db");
$dbh_train = new PDO("sqlite:../db/train.db");
$dbh_seat = new PDO("sqlite:../db/seat.db");
$dbh_book = new PDO("sqlite:../db/booking.db");

if($_SERVER['REQUEST_METHOD']=="POST"){
  if($_POST['action']=='Add'){
    error_reporting(0);
    $encrypt = crypt($_POST['new_pwd']);
    $sth = $dbh->prepare("INSERT INTO tb1(account,pwd) values(?,?)");
    $sth->execute(array($_POST['new_user'], $encrypt));
  }else if($_POST['action']=='Delete'){
    $sth = $dbh->prepare("DELETE FROM tb1 where account = ?");
    if (isset($_POST['del'])){
      foreach ($_POST['del'] as $i) {
        $sth->execute(array($i));
      }
    }
  }else if($_POST['action']=='Add_train'){
    $sth_train = $dbh_train->prepare("INSERT INTO tb1(id,type,start,t1,t2,t3,t4,t5,t6,seat,
      direct) values(?,?,?,?,?,?,?,?,?,?,?)");
    $sth_train->execute(array(
      $_POST['new_train'],
      $_POST['train_type'],
      $_POST['start_time'],
      $_POST['sta1'],
      $_POST['sta2'],
      $_POST['sta3'],
      $_POST['sta4'],
      $_POST['sta5'],
      $_POST['sta6'],
      $_POST['seat_num'],
      $_POST['direct']
    ));
  }else if($_POST['action']=='Add_book'){
    $sth_book = $dbh_book->prepare("INSERT INTO book(id,user,car,start,dest,day,adult,child,old
      ,paid,seats) values(?,?,?,?,?,?,?,?,?,?,?)");
    $sth_book->execute(array(
      $_POST['new_book'],
      $_POST['user_name'],
      $_POST['car_num'],
      $_POST['start_s'],
      $_POST['dest_s'],
      0,
      $_POST['adult_ticket'],
      $_POST['child_ticket'],
      $_POST['old_ticket'],
      $_POST['paid'],
      $_POST['seats']
    ));
  }else if($_POST['action']=='Delete_train'){
    $sth_train = $dbh_train->prepare("DELETE FROM tb1 where id = ?");
    if (isset($_POST['del_train'])){
      foreach ($_POST['del_train'] as $i) {
        $sth_train->execute(array($i));
      }
    }
  }else if($_POST['action']=="Delete_book"){
    $sth_book = $dbh_book->prepare("DELETE FROM book where id = ?");
    if (isset($_POST['del_book'])){
      foreach ($_POST['del_book'] as $i) {
        $sth_book->execute(array($i));
      }
    }
  }
}
?>

<p>Add user...</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<p>New User ID: <input type="text" name="new_user" required></p>
<p>New Password: <input type="password" name="new_pwd" required></p>
<input type="submit" name="action" value="Add">
</form>
<hr>

<p>Add train...</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<p>New train ID: <input type="number" name="new_train" required></p>
<p>Type:
  <select name="train_type" required>
  <option value="tze">自強</option>
  <option value="chu">莒光</option>
  <option value="local">區間</option></select>
</p>
<p>Start: <input type="number" name="start_time" required></p>
<p>第一站: <input type="number" name="sta1" required></p>
<p>第二站: <input type="number" name="sta2" required></p>
<p>第三站: <input type="number" name="sta3" required></p>
<p>第四站: <input type="number" name="sta4" required></p>
<p>第五站: <input type="number" name="sta5" required></p>
<p>第六站: <input type="number" name="sta6" required></p>
<p>Seat: <input type="number" name="seat_num" min="1" max="30" required></p>
<p>Direction(South=1,North=0): <input type="number" name="direct" min="0" max="1" required></p>
<input type="submit" name="action" value="Add_train">
</form>
<hr>

<p>Add booking...</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<p>New book ID: <input type="number" name="new_book" required></p>
<p>User: <input type="text" name="user_name" required></p>
<p>Car: <input type="number" name="car_num" required></p>
<p>Start: <input type="text" name="start_s" required></p>
<p>Dest: <input type="text" name="dest_s" required></p>
<p>adult: <input type="number" name="adult_ticket" required></p>
<p>child: <input type="number" name="child_ticket" required></p>
<p>old: <input type="number" name="old_ticket" required></p>
<p>paid: <input type="number" name="paid" required></p>
<p>seats: <input type="text" name="seats" required></p>
<input type="submit" name="action" value="Add_book">
</form>
<hr>

<!-- user -->
<?php
echo '使用者資料<br>';
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table border="1"><tr><td><b>ID</b></td><td><b>Password</b></td><td>Delete</td></tr>
<?php
$sth = $dbh->prepare("SELECT * from tb1");
$sth->execute();
while($row = $sth->fetch()){
  echo "<tr><td>";
  echo "$row[0]";
  echo "</td><td>";
  echo $row[1];
  echo "</td><td>";
  echo "<input type='checkbox' name='del[]' value=".$row[0].">";
  echo "</td></tr>";
}
?>
</table>
<input type="submit" name="action" value="Delete">
</form><br>

<!--train-->
<?php
echo '火車班次<br>';
echo '南下<br>';
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table border="1"><tr><td><b>ID</b></td><td><b>Type</b></td><td><b>Start</b></td>
  <td><b>Taipei</b></td><td><b>Taoyuan</b></td><td><b>Hsinchu</b></td><td><b>Taichung</b></td>
  <td><b>Tainan</b></td><td><b>Kaohsiung</b></td><td><b>seat</b></td><td>Delete</td></tr>
<?php
$sth_train = $dbh_train->prepare("SELECT * from tb1 where direct=(?)");
$sth_train->execute(array(1));
while($row = $sth_train->fetch()){
  echo "<tr><td>";echo "$row[0]";echo "</td><td>";echo $row[1];
  echo "</td><td>";echo $row[2];echo "</td><td>";echo $row[3];
  echo "</td><td>";echo $row[4];echo "</td><td>";echo $row[5];
  echo "</td><td>";echo $row[6];echo "</td><td>";echo $row[7];
  echo "</td><td>";echo $row[8];echo "</td><td>";echo $row[9];
  echo "</td><td>";
  echo "<input type='checkbox' name='del_train[]' value=".$row[0].">";
  echo "</td></tr>";
}
?>
</table>
<br>
<?php
echo '北上<br>';
?>
<table border="1"><tr><td><b>ID</b></td><td><b>Type</b></td><td><b>Start</b></td>
  <td><b>Kaohsiung</b></td><td><b>Tainan</b></td><td><b>Taichung</b></td><td><b>Hsinchu</b></td>
  <td><b>Taoyuan</b></td><td><b>Taipei</b></td><td><b>seat</b></td><td>Delete</td></tr>
<?php
$sth_train = $dbh_train->prepare("SELECT * from tb1 where direct=(?)");
$sth_train->execute(array(0));
while($row = $sth_train->fetch()){
  echo "<tr><td>";echo "$row[0]";echo "</td><td>";echo $row[1];
  echo "</td><td>";echo $row[2];echo "</td><td>";echo $row[3];
  echo "</td><td>";echo $row[4];echo "</td><td>";echo $row[5];
  echo "</td><td>";echo $row[6];echo "</td><td>";echo $row[7];
  echo "</td><td>";echo $row[8];echo "</td><td>";echo $row[9];
  echo "</td><td>";
  echo "<input type='checkbox' name='del_train[]' value=".$row[0].">";
  echo "</td></tr>";
}
?>
</table>
<input type="submit" name="action" value="Delete_train">
</form><br>

<!---booking--->
<?php
echo '使用者訂票紀錄<br>';
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table border="1"><tr><td><b>ID</b></td><td><b>User</b></td><td><b>Car</b></td><td><b>Start</b></td>
  <td><b>Dest</b></td><td><b>Date</b></td><td><b>Adult</b></td><td><b>Child</b></td>
  <td><b>Old</b></td><td><b>Paid</b></td><td><b>Seats</b></td><td><b>Delete</b></td></tr>
<?php
$sth_book = $dbh_book->prepare("SELECT * from book");
$sth_book->execute();
while($row = $sth_book->fetch()){
  echo "<tr><td>";echo "$row[0]";echo "</td><td>";echo $row[1];
  echo "</td><td>";echo $row[2];echo "</td><td>";echo $row[3];
  echo "</td><td>";echo $row[4];echo "</td><td>";echo $row[5];
  echo "</td><td>";echo $row[6];echo "</td><td>";echo $row[7];
  echo "</td><td>";echo $row[8];echo "</td><td>";echo $row[9];
  echo "</td><td>";echo $row[10];
  echo "</td><td>";
  echo "<input type='checkbox' name='del_book[]' value=".$row[0].">";
  echo "</td></tr>";
}
?>
</table>
<input type="submit" name="action" value="Delete_book">
</form><br>

<?php
echo "班次座位".'<br>';
for($a=1; $a<=14; $a++){
  if($a<10){
    $p = "SELECT * from t10".$a; $sth_seat = $dbh_seat->prepare($p);
    echo "t10".$a.': ';
  }else{
    $p = "SELECT * from t1".$a; $sth_seat = $dbh_seat->prepare($p);
    echo "t1".$a.': ';
  }
  $sth_seat->execute();
  $row = $sth_seat->fetch();
  for($i=0; $i<30; $i++){
    echo $row[$i];
  }
  echo '<br>';
}
?>

<?php
$logout = new Logout();
?>

</body>
</html>
