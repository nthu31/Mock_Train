<?php
include 'config.php';
session_start();
if (!isset($_SESSION['is_login'])){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}else if ($_SESSION['is_login']==0){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}
?>

<form method="post" action="timetable.php">
  <p>New account: <input type="text" minlength=5 name="new_ac" required></p>
  <p>New password: <input type="password" minlength=5 name="new_pwd" required></p>
  <p>Verify password: <input type="password" minlength=5 name="v_pwd" required></p>
  <input type="submit" name="action" value="modify_info">
</form>

<form method="post" action="timetable.php">
  <input type="submit" name="action" value="return">
</form>
