<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
  <style>
    table, tr, th{
      border: 1px solid black;
      border-collapse: collapse;
    }
  </style>
</head>

<body>

<?php
session_start();
if (!isset($_SESSION['is_login'])){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}else if ($_SESSION['is_login']==0){
  header("Location: http://ss.cs.nthu.edu.tw/~MockTrain/try/index.php");
  exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
  if($_POST['action']=='modify_info'){
    $user = new Db();
    $book = new Db_book();
    if($user->check_user($_POST['new_ac'])){
      echo 'This id has been used<br>';
    }else{
      if($_POST['new_pwd']==$_POST['v_pwd']){
        error_reporting(0);
        $encrypt = crypt($_POST['new_pwd']);
        $sth = $user->dbh->prepare("UPDATE tb1 set account=(?), pwd=(?) where account=(?)");
        $sth->execute(array($_POST['new_ac'], $encrypt, $_SESSION['user']));
        $sth_book = $book->dbh->prepare("UPDATE book set user=(?)");
        $sth_book->execute(array($_POST['new_ac']));
        echo 'Modify your information successfully<br>';
        $_SESSION['user'] = $_POST['new_ac'];
      }else{
        echo "Wrong Validation passpord...<br>";
      }
    }
  }else if($_POST['action']=='Pay_book'){
    $book = new Db_book();
    $sth_book = $book->dbh->prepare("UPDATE book set paid=(?) where id=(?)");
    if (isset($_POST['pay_book'])){
      foreach ($_POST['pay_book'] as $i) {
        $sth_book->execute(array(1,$i));
      }
    }
  }else if($_POST['action']=='Pay_back'){
    $book = new Db_book();
    $sth_book = $book->dbh->prepare("SELECT * from book where id=(?)");
    if (isset($_POST['pay_back'])){
      foreach ($_POST['pay_back'] as $i) {
        $sth_book->execute(array($i));
        $info = $sth_book->fetch();
        $seat = new Db_seat();
        for($j=0; $j<strlen($info[10])/3; $j++){
          $one_seat = $info[10][$j*3].$info[10][$j*3+1].$info[10][$j*3+2];
          $text = "UPDATE "."t".$info[2]." set ".$one_seat."=0";
          $sth_seat = $seat->dbh->prepare($text);
          $sth_seat->execute();
        }
        $db_train = new Db_train();
        $sth_train = $db_train->dbh->prepare("SELECT * from tb1 where id=(?)");
        $sth_train->execute(array($info['car']));
        $row = $sth_train->fetch();
        $sth_train = $db_train->dbh->prepare("UPDATE tb1 set seat=(?) where id=(?)");
        $sth_train->execute(array(
          $row[9]+$info['adult']+$info['child']+$info['old'],
          $info['car']
        ));
        $sth_book = $book->dbh->prepare("DELETE FROM book where id = ?");
        if (isset($_POST['pay_back'])){
          foreach ($_POST['pay_back'] as $i) {
            $sth_book->execute(array($i));
          }
        }
      }
    }
  }
}
?>
<p>Hello <?php echo $_SESSION['user'] ?>!</p>
<?php
$modify_butt = new Modify();
?>
<form name="post1" method="POST" action="./checkcode.php">
  <table>
    <tr>
      <th>起迄站</th>
      <th>
        起點：
        <select name="start" id="loc">
          <option value="Taipei">台北</option>
          <option value="Taoyuan">桃園</option>
          <option value="Hsinchu">新竹</option>
          <option value="Taichung">台中</option>
          <option value="Tainan">台南</option>
          <option value="Kaohsiung">高雄</option>
        </select>
        終點：
        <select name="dest" id="loc">
          <option value="Taipei">台北</option>
          <option value="Taoyuan">桃園</option>
          <option value="Hsinchu">新竹</option>
          <option value="Taichung">台中</option>
          <option value="Tainan">台南</option>
          <option value="Kaohsiung">高雄</option>
        </select>
      </th>
    </tr>
    <tr>
      <th>車種</th>
      <th>
        <select name="train">
          <option value="tze">自強</option>
          <option value="local">區間</option>
          <option value="chu">莒光</option>
        </select>
      </th>
    </tr>
    <tr>
      <th>時間</th>
      <th>
        <select name='hour'>
          <option value='00'>00</option><option value='01'>01</option>
          <option value='02'>02</option><option value='03'>03</option>
          <option value='04'>04</option><option value='05'>05</option>
          <option value='06'>06</option><option value='07'>07</option>
          <option value='08'>08</option><option value='09'>09</option>
          <option value='10'>10</option><option value='11'>11</option>
          <option value='12'>12</option><option value='13'>13</option>
          <option value='14'>14</option><option value='15'>15</option>
          <option value='16'>16</option><option value='17'>17</option>
          <option value='18'>18</option><option value='19'>19</option>
          <option value='20'>20</option><option value='21'>21</option>
          <option value='22'>22</option><option value='23'>23</option>
        </select>:
        <select name="minute">
          <option value='00'>00</option><option value='01'>01</option>
          <option value='02'>02</option><option value='03'>03</option>
          <option value='04'>04</option><option value='05'>05</option>
          <option value='06'>06</option><option value='07'>07</option>
          <option value='08'>08</option><option value='09'>09</option>
          <option value='10'>10</option><option value='11'>11</option>
          <option value='12'>12</option><option value='13'>13</option>
          <option value='14'>14</option><option value='15'>15</option>
          <option value='16'>16</option><option value='17'>17</option>
          <option value='18'>18</option><option value='19'>19</option>
          <option value='20'>20</option><option value='21'>21</option>
          <option value='22'>22</option><option value='23'>23</option>
          <option value='24'>24</option><option value='25'>25</option>
          <option value='26'>26</option><option value='27'>27</option>
          <option value='28'>28</option><option value='29'>29</option>
          <option value='30'>30</option><option value='31'>31</option>
          <option value='32'>32</option><option value='33'>33</option>
          <option value='34'>34</option><option value='35'>35</option>
          <option value='36'>36</option><option value='37'>37</option>
          <option value='38'>38</option><option value='39'>39</option>
          <option value='40'>40</option><option value='41'>41</option>
          <option value='42'>42</option><option value='43'>43</option>
          <option value='44'>44</option><option value='45'>45</option>
          <option value='46'>46</option><option value='47'>47</option>
          <option value='48'>48</option><option value='49'>49</option>
          <option value='50'>50</option><option value='51'>51</option>
          <option value='52'>52</option><option value='53'>53</option>
          <option value='54'>54</option><option value='55'>55</option>
          <option value='56'>56</option><option value='57'>57</option>
          <option value='58'>58</option><option value='59'>59</option>
        </select>
      </th>
    </tr>
    <tr>
      <th>票種</th>
      <th>
        成人：<input type="number" name="adult" min="0" max="10" value="0"/>
        孩童：<input type="number" name="child" min="0" max="10" value="0"/>
        敬老：<input type="number" name="older" min="0" max="10" value="0"/>
      </th>
    </tr>
  </table>

  <p>請輸入下列字樣</p>
  <p><img id='imcode' src="captcha.php" onclick="refresh_code()"/>
    <br/>點擊圖片可更換驗證碼</p>
  <input type="text" name="checkword" size="10" maxlength="10"/>
  <input type="submit" name="Submit" value="送出"/>
</form>

<!-- <p id="demo"></p> -->

<?php
$logout = new Logout();
$select = new Db_book();
$sth = $select->dbh->prepare("SELECT * FROM book where user=(?)");
$sth->execute(array($_SESSION['user']));
?>
<br>
<p><b>Your Order :</b></p>
<form method="POST" action="timetable.php">
  <table border="1"><tr><td><b>ID</b></td><td><b>User</b></td><td><b>Car</b></td><td><b>
    Start</b></td><td><b>Dest</b></td><td><b>adult</b></td><td><b>child
    </b></td><td><b>old</b></td><td><b>繳費情形</b></td><td><b>繳費</b></td><td><b>退票</b></td></tr>
  <?php
  while($row = $sth->fetch()){
    echo "<tr><td>";echo "$row[0]";echo "</td><td>";echo $row[1];
    echo "</td><td>";echo $row[2];echo "</td><td>";echo $row[3];
    echo "</td><td>";echo $row[4];echo "</td><td>";echo $row[6];
    echo "</td><td>";echo $row[7];echo "</td><td>";echo $row[8];
    if($row[9]==0){
      echo "</td><td>未繳費";echo "</td><td>";
      echo "<input type='checkbox' name='pay_book[]' value=".$row[0].">";
      echo "</td><td>";
      echo "<input type='checkbox' name='pay_back[]' value=".$row[0].">";
      echo "</td></tr>";
    }else{
      echo "</td><td>已繳費";echo "</td><td>";
      echo "---";echo "</td><td>";
      echo "---";echo "</td></tr>";
    }
  }?></table>
  <input type="submit" name="action" value="Pay_book">
  <input type="submit" name="action" value="Pay_back">
</form>

<script>
  /*var act = document.getElementById("loc");
  document.getElementById("demo").innerHTML = act.value;

  act.addEventListener("change", function(){
    var select = act.value;
    document.getElementById("demo").innerHTML = select;
  });*/
  function refresh_code(){
    document.getElementById("imcode").src='captcha.php';
  }
</script>

</body>
</html>
