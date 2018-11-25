<?php
	session_start();

	if(!isset($_SESSION['pay']) || $_SESSION['pay'] != true ){
		header('Location: remittance.php');
		exit();
	}

	unset($_SESSION['pay']);
	$_SESSION['payResponse'] = true;

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
	<div class="descriptionWeb">Sprawdź poprawność danych: </div>
	<div class="welcome">
		Nazwa odbiorcy: <?php echo $_SESSION['payName'] ?> <br />
		Rachunek odbiorcy: <?php echo $_SESSION['payAccount'] ?> <br />
		Kwota: <?php echo $_SESSION['payAmount'] ?> zł <br />
		Tytuł: <?php echo $_SESSION['payTitle'] ?> <br /> <br />
	</div>
	<a href="remittance.php" class="button">Wstecz</a>
	<a href="payResponse.php" class="button">Akceptuj</a>



</div>

<?php
	echo $myPage->end();
?>
