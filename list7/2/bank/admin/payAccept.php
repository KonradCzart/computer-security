<?php

if(!isset($_GET['accept']) || !isset($_GET['accept']) || !isset($_GET['userId']) ) {
  header('Location: adminpanel.php');
  exit();
}

$accept =  $_GET['accept'];
$remittanceId = $_GET['remittance'];
$userId = $_GET['userId'];


require_once "../php/connect.php";
$connect = DataBaseConnection::getInstance();

$status = PayStatus::unsuccessful;

if($accept == 1){
  echo $accept;
  $status = PayStatus::successful;
}

$connect->updateRemittance($remittanceId, $status, $userId);

header('Location: adminpanel.php');

?>
