<?php
require_once "security.php";
require_once "bank.php";

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
    $nameDB = "bankDB";
    $this->db = new mysqli($hostDB, $userDB, $passwordDB, $nameDB);

  }

  public function closeDataBase(){
    $this->db->close();
    $this->instance = null;
  }

  public function login($login, $password){

    $loginSafe = htmlentities($login, ENT_QUOTES, "UTF-8");
    $loginSafe= mysqli_real_escape_string($this->db, $loginSafe);

    $sql = "SELECT * FROM users INNER JOIN salts ON users.id = salts.userId WHERE login='$loginSafe'";

    if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        $row = $result->fetch_assoc();

        $salt = $row['salt'];
        $dbPassword = $row['password'];

        $isGoodPassword = HashWithSalt::checkPassword($password, $salt, $dbPassword);
        if($isGoodPassword){
          $userData['id'] = $row['id'];
          $userData['login'] = $row['login'];
          $userData['name'] = $row['name'];
          $userData['account'] = $row['number'];
          $userData['balance'] = $row['balance'];
          return $userData;
        }

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

    $sql = "INSERT INTO users VALUES(NULL,'$login', '$passwordSalt', '$name', '$email', '$accountNumber', 1000.0, '$peselSalt' )";

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
    $sql = "INSERT INTO remittance VALUES(NULL,'$userId', '$account', '$name', '$value', '$title', NULL)";

    if($this->db->query($sql)){
      return true;
    }
    return false;
  }

  public function updateAccount($userId, $amount, $cost){
    $newAmount = $amount - $cost;
    $sql = "UPDATE users SET balance='$newAmount' WHERE id='$userId'";

    if($this->db->query($sql)){
      return $newAmount;
    }
    return false;

  }

  public function getRemittance($userId){
    $sql =  "SELECT * FROM remittance WHERE userId='$userId' ORDER BY remittance.date DESC";

    $arrayRow;
    $i = 0;
      if($result = $this->db->query($sql)){
      $numberUsers = $result->num_rows;
      if($numberUsers > 0){
        while($row = $result->fetch_assoc() ){
          $arrayRow[$i][0] = $row['recipientName'];
          $arrayRow[$i][1] = $row['recipientAccount'];
          $arrayRow[$i][2] = $row['value'];
          $arrayRow[$i][3] = $row['title'];
          $arrayRow[$i][4] = $row['date'];
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

}


?>
