<?php
  session_start();

	if(!isset($_SESSION['signIn'])){
		header('Location: ../index.php');
		exit();
	}

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
	<div class="menu">
		<a href="logout.php" class="button">Wyloguj się</a></p>
	</div>

	<div class="welcome">
		<?php
		  echo "<p>Witaj <b>".$_SESSION['userName'].'!</b></p>';
		  echo '<p>Twój numer konta to: <div id="account">'.$_SESSION['userAccount'].'</div></p>';
		  echo "<p>Aktualny stan konta: ".$_SESSION['userBalance'].' zł </p>';
		?>
	</div>
	<a href="remittance.php" class="button">Wykonaj przelew!</a>
	<a href="history.php" class="button">Historia przelewów!</a>
</div>

<?php
	echo $myPage->end();
?>
