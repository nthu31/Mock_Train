<?php
class Login{
  function __construct(){
?>
    <form method="post" action="index.php">
      <p>Account: <input type="text" name="account" required></p>
      <p>Password: <input type="password" name="password" required></p>
      <input type="submit" name="action" value="login">
    </form>
    <form method="post" action="index.php">
      <p>I want to sign up <input type="submit" name="action" value="logup"></p>
    </form>
<?php
  }
}

class Logup{
  function __construct(){
?>
    <form method="post" action="index.php">
      <p>New account: <input type="text" minlength=5 name="account" required id='new_ac'></p>
      <p>New password: <input type="password" minlength=5 name="password" required id='new_pwd'></p>
      <input type="submit" name="action" value="confirm">
    </form>
    <form method="post" action="index.php">
      <input type="submit" name="action" value="return">
    </form>
<?php
  }
}

class Logout{
  function __construct(){
?>
  <form method="POST" action="index.php">
    <input type="submit" name="action" value="logout">
  </form>
<?php
  }
}

class Modify{
  function __construct(){
?>
  <form method="post" action="modify.php">
    <input type="submit" name="action" value="modify">
  </form><br>
<?php
  }
}
?>
