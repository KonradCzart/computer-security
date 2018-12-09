<?php
require_once "security.php";
require_once "bank.php";

abstract class PayStatus{
    const realize = 1;
    const successful = 2;
    const unsuccessful = 3;
}


class DataBaseConnection{

  private static $instance;
  private $db;

  public static function getInstance(){
    if(self::$instance == null){
      self::$instance = new DataBaseConnection();
    }
    return self::$instance;
  }

  private function __construct(){
    $hostDB = "localhost:3306";
    $userDB = "root";
    $passwordDB = "root123";
    $nameDB = "bankDB2";
    $this->db = new mysqli($hostDB, $userDB, $passwordDB, $nameDB);

  }

  public function closeDataBase(){
    $this->db->close();
    $this->instance = null;
  }

  public function login($login, $password){

    //$loginSafe = htmlentities($login, ENT_QUOTES, "UTF-8");
    //$loginSafe= mysqli_real_escape_string($this->db, $loginSafe);

    $loginSafe = $login;

    $sql = "SELECT * FROM users INNER JOIN salts ON users.id = salts.userId WHERE login='$loginSafe' AND password='$password'";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();

        // $salt = $row['salt'];
        // $dbPassword = $row['password'];
        //
        // $isGoodPassword = HashWithSalt::checkPassword($password, $salt, $dbPassword);
        // if($isGoodPassword){
          $userData['id'] = $row['id'];
          $userData['login'] = $row['login'];
          $userData['name'] = $row['name'];
          $userData['account'] = $row['number'];
          $userData['balance'] = $row['balance'];
          $userData['admin'] = $row['admin'];
          return $userData;
        // }

        $result->free_result();
      }
    }
    return false;
  }

  public function registration($login, $password, $name, $email, $pesel){
    $salt = HashWithSalt::getSalt(32);
    $passwordSalt = HashWithSalt::hashPassword($password, $salt);
    $peselSalt = HashWithSalt::hashPassword($pesel, $salt);
    $accountNumber = Bank::getNewAccountNumber();

    $sql = "INSERT INTO users VALUES(NULL,'$login', '$password', '$name', '$email', '$accountNumber', 1000.0, '$peselSalt', 0)";

    if($this->db->query($sql)){
      $id = $this->getId($login);
      echo $id;
      $sql2 = "INSERT INTO salts VALUES(NULL,'$id', '$salt')";
      if($this->db->query($sql2)){
        return true;
      }
    }
    return false;
  }

  public function getId($login):int {

    $loginSafe= mysqli_real_escape_string($this->db, $login);
    $sql = "SELECT * FROM users WHERE login='$loginSafe'";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $result->free_result();

        return $userId;
      }
    }
    return false;
  }

  public function isEmail($email):bool {
        $emailSafe= mysqli_real_escape_string($this->db, $email);
        $sql = "SELECT * FROM users WHERE email='$emailSafe'";

        if($result = $this->db->query($sql)){
          $numberUsers = $result->num_rows;
          return boolval($numberUsers);
        }

        return false;
  }

  public function isLogin($login):bool {
        $loginSafe= mysqli_real_escape_string($this->db, $login);
        $sql = "SELECT * FROM users WHERE login='$loginSafe'";

        if($result = $this->db->query($sql)){
          $numberUsers = $result->num_rows;
          return boolval($numberUsers);
        }

        return false;
  }

  public function lastUserId():int {
    $sql =  "SELECT * FROM users ORDER BY id DESC LIMIT 1";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $result->free_result();

        return $userId;
      }
    }

    return 0;
  }


  public function addRemittance($userId, $account, $name, $value, $title){
    $safeTitle =  mysqli_real_escape_string($this->db, $title);

    $status = PayStatus::realize;
    $sql = "INSERT INTO remittance VALUES(NULL,'$userId', '$account', '$name', '$value', '$title', NULL, '$status')";

    if($this->db->query($sql)){
      return true;
    }
    return false;
  }

  public function updateAccount($userId, $balance, $cost){
    $newBalance = $balance - $cost;
    $sql = "UPDATE users SET balance='$newBalance' WHERE id='$userId'";

    if($this->db->query($sql)){
      return $newBalance;
    }
    return false;

  }


  public function getRemittance($userId, $status){
    $sql =  "SELECT * FROM remittance JOIN payStatus AS ps ON statusId = ps.id WHERE statusId='$status' AND userId='$userId' ORDER BY remittance.date DESC";

    $arrayRow;
    $i = 0;
      if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        while($row = $result->fetch_assoc() ){
          $arrayRow[$i][0] = $row['id'];
          $arrayRow[$i][1] = $row['recipientName'];
          $arrayRow[$i][2] = $row['recipientAccount'];
          $arrayRow[$i][3] = $row['value'];
          $arrayRow[$i][4] = $row['title'];
          $arrayRow[$i][5] = $row['date'];
          $arrayRow[$i][6] = $row['name'];
          $i++;
        }
        $result->free_result();
        return $arrayRow;
      }
    }
    return false;
  }

  public function getAllRemittance($status){
    $sql =  "SELECT *, remittance.id AS rid FROM remittance JOIN payStatus AS ps ON statusId = ps.id WHERE statusId='$status' ORDER BY remittance.date DESC";

    $arrayRow;
    $i = 0;
      if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        while($row = $result->fetch_assoc() ){
          $arrayRow[$i][0] = $row['rid'];
          $arrayRow[$i][1] = $row['userId'];
          $arrayRow[$i][2] = $row['recipientName'];
          $arrayRow[$i][3] = $row['recipientAccount'];
          $arrayRow[$i][4] = $row['value'];
          $arrayRow[$i][5] = $row['title'];
          $arrayRow[$i][6] = $row['date'];
          $arrayRow[$i][7] = $row['name'];
          $i++;
        }
        $result->free_result();
        return $arrayRow;
      }
    }
    return false;
  }

  public function changePassword($login, $newPassword, $pesel, $email){
    $sql =  "SELECT * FROM users JOIN salts ON users.id = salts.userId WHERE users.login='$login'";

    $goodChange = false;

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;

      if($numberUsers > 0){
        $row = $result->fetch_assoc();
        if($email == $row['email']){
            if(HashWithSalt::checkPassword($pesel, $row['salt'], $row['pesel'])){
            //change password
            $newSafePassword = HashWithSalt::hashPassword($newPassword, $row['salt']);
            $userId = $row['id'];
            $sql = "UPDATE users SET password='$newSafePassword' WHERE id='$userId'";
            return $this->db->query($sql);
          }
        }
      }
      $result->free_result();
    }
    return $goodChange;
  }

  public function updateRemittance($remittanceId, $status, $userId){
    $sql = "UPDATE remittance SET statusID='$status' WHERE id='$remittanceId'";

    if($status == PayStatus::unsuccessful){
      $balance = $this->getBalance($userId);
      $cost = $this->getRemittanceCost($remittanceId);
      $cost = $cost*(-1);
      $this->updateAccount($userId, $balance, $cost);
    }

    if($this->db->query($sql)){
      return true;
    }
    return false;
  }

  public function getBalance($userId){
    $sql =  "SELECT balance FROM users WHERE id='$userId'";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();
        $balance = $row['balance'];
        $result->free_result();
        return $balance;
      }
    }
    return 0;
  }

  public function getRemittanceCost($remittanceId){
    $sql =  "SELECT value FROM remittance WHERE id='$remittanceId'";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();
        $cost = $row['value'];
        $result->free_result();
        return $cost;
      }
    }
    return 0;
  }

}


?>
