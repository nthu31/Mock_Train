<!DOCTYPE html>
<html>
<body>

<?php
$dbh = new PDO("sqlite:db/note.db");
if($_SERVER['REQUEST_METHOD']=="POST"){
  if($_POST['action']=='Add'){
    $sth = $dbh->prepare("INSERT INTO tb1(content) values(?)");
    $sth->execute(array($_POST['text']));
  }else if($_POST['action']=='Delete'){
    $sth = $dbh->prepare("DELETE FROM tb1 where ID = ?");
    if (isset($_POST['del'])){
      foreach ($_POST['del'] as $i) {
        $sth->execute(array($i));
      }
    }
  }
}
?>

<p>Add some new note...</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<textarea name="text">
</textarea><br>
<input type="submit" name="action" value="Add">
<hr>

<table border="1"><tr><td><b>ID</b></td><td><b>CONTENT</b></td><td>Delete</td></tr>
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
</form>

</body>
</html>
