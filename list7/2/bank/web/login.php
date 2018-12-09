<?php
  session_start();

  require_once "../php/connect.php";
  if(!isset($_POST['username']) || !isset($_POST['password'])) {
    header('Location: ../index.php');
    exit();
  }

  $login = $_POST['username'];
  $password = $_POST['password'];

  $connect = DataBaseConnection::getInstance();

  $userData = $connect->login($login, $password);
  if($userData){
    $_SESSION['signIn'] = TRUE;
    $_SESSION['userId'] = $userData['id'];
    $_SESSION['userLogin'] = $userData['login'];
    $_SESSION['userAccount'] = $userData['account'];
    $_SESSION['userName'] = $userData['name'];
    $_SESSION['userBalance'] = $userData['balance'];
    $_SESSION['userAdmin'] = $userData['admin'];

    unset($_SESSION['error']);
    header('Location: main.php');
  }
  else{
    $_SESSION['signIn'] = FALSE;
    $_SESSION['error'] = '<div class="error">Nieprawidłowy login bądź hasło.</div>';
    header('Location: ../index.php');
  }

  $connect->closeDataBase();

?>
