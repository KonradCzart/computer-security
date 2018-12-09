<?php
require_once "connect.php";

class Bank{
  private const checksum = "00";
  private const bankNumber = "76843994";

  public static function getNewAccountNumber(){
    $rand1 = rand(100,999);
    $rand2 = rand(100,999);
    $rand3 = rand(100,999);

    $connect = DataBaseConnection::getInstance();
    $invID = $connect->lastUserId();
    $invID = str_pad($invID, 7, '0', STR_PAD_LEFT);

    $result = self::checksum.self::bankNumber.$rand1.$invID.$rand2.$rand3;

    return $result;
  }

}

?>
