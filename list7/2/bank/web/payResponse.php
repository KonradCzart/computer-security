<?php
  session_start();


  if(!isset($_SESSION['payResponse']) || $_SESSION['payResponse'] != true ){
    header('Location: main.php');
    exit();
  }

	require_once "../php/connect.php";
	$connect = DataBaseConnection::getInstance();

	$result = $connect->addRemittance($_SESSION['userId'], $_SESSION['payAccount'], $_SESSION['payName'], $_SESSION['payAmount'], $_SESSION['payTitle']);
	$balance = $connect->getBalance($_SESSION['userId']);
	$_SESSION['userBalance'] = $connect->updateAccount($_SESSION['userId'],$balance,$_SESSION['payAmount']);

	unset($_SESSION['payResponse']);
	unset($_SESSION['saveName']);
	unset($_SESSION['saveAccount']);
	unset($_SESSION['saveZl']);
	unset($_SESSION['saveGr']);
	unset($_SESSION['saveTitle']);

	require_once "../php/page.php";
	$DESCRIPTION = "Nowoczesny bank-logowanie";
	$myPage = new MyPage("Konrad Czart");
	$myPage->setDescription($DESCRIPTION);
	$myPage->addCSS("../css/reset.css");
	$myPage->addCSS("../css/style.css");
	echo $myPage->begin();
?>

<div class="mainContainer">
	<div class="logo">Nowoczesny bank!</div>

	<div class="descriptionWeb">
	<?php
		if($result){
			echo "Wykonano przelew:";
		}
		else{
			echo "Nieudało się wykonać przelewu!!";
		}
	?>
	</div>
	<div class="welcome">
		Nazwa odbiorcy: <?php echo $_SESSION['payName'] ?> <br />
		Rachunek odbiorcy: <?php echo $_SESSION['payAccount'] ?> <br />
		Kwota: <?php echo $_SESSION['payAmount'] ?> zł <br />
		Tytuł: <?php echo $_SESSION['payTitle'] ?> <br /> <br />
	</div>

	<a href="main.php" class="button">Strona główna</a>


</div>

<?php
	echo $myPage->end();
?>
