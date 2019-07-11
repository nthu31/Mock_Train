<?php
include "db.php";

$db = new Db_seat();
$sth = $db->dbh->prepare("CREATE TABLE t111(
  A01 int,A02 int,A03 int,A04 int,A05 int,A06 int,A07 int,A08 int,A09 int,A10 int,
  B01 int,B02 int,B03 int,B04 int,B05 int,B06 int,B07 int,B08 int,B09 int,B10 int,
  C01 int,C02 int,C03 int,C04 int,C05 int,C06 int,C07 int,C08 int,C09 int,C10 int
)");
$sth->execute();
$sth = $db->dbh->prepare("INSERT into t111 values(
  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
  )");
$sth->execute();

?>
