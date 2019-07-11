<?php
class Web {

  function __construct(){
    session_start();
    if(!isset($SESSION['is_login'])){
        $_SESSION['is_login'] = 0;
    }
    if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=='login'){
      $db = new Db();
      $sth = $db->dbh->prepare("SELECT * from tb1");
      $sth->execute();
      $n = 0;
      while($row = $sth->fetch()){
        if($row['account']==$_POST['account']){
          $id = $row['account'];
          $pwd = $row['pwd'];
          $n = 1;
          break;
        }
      }
      if ($n==1){
        if(crypt($_POST['password'], $pwd)==$pwd){
          $_SESSION['is_login'] = 1;
          $_SESSION['user'] = $id;
          if ($id=="admin"){
            header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/admin.php");
            exit;
          }else{
            header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/timetable.php");
            exit;
          }
        }else{
          $login = new Login();
          echo 'Wrong password<br>';
        }
      }else{
        $login = new Login();
        echo 'No such id<br>';
      }
    }else if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=='logup'){
      $logup = new Logup();
    }else if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=='confirm'){
      $db = new Db();
      if ($db->check_user($_POST['account'])){
        $logup = new Logup();
        echo 'This id has been used<br>';
      }else{
        $sth = $db->dbh->prepare("INSERT INTO tb1(account,pwd) values(?,?)");
        error_reporting(0);
        $encrypt = crypt($_POST['password']);
        $sth->execute(array($_POST['account'], $encrypt));
        $login = new Login();
      }
    }else if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=='logout'){
      $_SESSION['user'] = '';
      $_SESSION['is_login'] = 0;
      $login = new Login();
    }else if ($_SESSION['is_login']==1){
      echo 'Hello '. $_SESSION['user'] .'<br>';
      $logout = new Logout();
    }else if ($_SESSION['is_login']==0){
      $login = new Login();
    }else if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=='return'){
      $login = new Login();
    }
  }

}
?>
