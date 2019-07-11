<?php
error_reporting(0);
$a = crypt('asd12125');
echo "$a<br>";
$_SESSION['ans'] = $a;
if(crypt('asd12125', $_SESSION['ans']) == $_SESSION['ans']){
  echo 'yesss';
}else{
  echo 'nope';
}
?>
