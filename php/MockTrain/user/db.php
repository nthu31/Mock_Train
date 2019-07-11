<?php
class Db{
  public $dbh;
  function __construct(){
    $this->dbh = new PDO("sqlite:../db/user.db");
  }
  public function check_user($id){
    $sth = $this->dbh->prepare("select * from tb1");
    $sth->execute();
    while($row = $sth->fetch()){
      if ($row['account']==$id){
        return true;
      }
    }
    return false;
  }
}

class Db_train{
  public $dbh;
  function __construct(){
    $this->dbh = new PDO("sqlite:../db/train.db");
  }
}

class Db_seat{
  public $dbh;
  function __construct(){
    $this->dbh = new PDO("sqlite:../db/seat.db");
  }
}

class Db_book{
  public $dbh;
  function __construct(){
    $this->dbh = new PDO("sqlite:../db/booking.db");
  }
}

?>
