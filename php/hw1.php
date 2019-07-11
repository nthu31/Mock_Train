<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
  <h4>Guess a number from 0 to 9</h4>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="number" name="num" min="0" max="9"><input type="submit" name="submit">
  </form>

  <?php
    $check = 0;
    if (!isset($_POST['num'])){
      $_POST['num'] = -1;
    }elseif ($_POST['num']==$_SESSION['ans']){
      $check = 1;
      echo "correct";
      $_SESSION['ans'] = rand(0, 9);
    }

    if(!isset($_SESSION['ans'])){
      $_SESSION['ans'] = rand(0, 9);
    }

    if($check==0 && $_SERVER["REQUEST_METHOD"]=="POST"){
        echo "wrong";
    }
    echo "<br>Hint: ". $_SESSION['ans'];
  ?>
</body>
</html>
