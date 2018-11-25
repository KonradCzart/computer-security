<?php
	session_start();

	if(isset($_SESSION['signIn']) && ($_SESSION['signIn']==true)){
		header('Location: web/main.php');
		exit();
	}

	require_once "php/page.php";
	$DESCRIPTION = "Nowoczesny bank-logowanie";
	$myPage = new MyPage("Konrad Czart");
	$myPage->setDescription($DESCRIPTION);
	$myPage->addCSS("css/reset.css");
	$myPage->addCSS("css/style.css");
	echo $myPage->begin();
?>

<div class="mainContainer"><main>
	<div class="logo">Nowoczesny Bank!</div>
	<div class="menu">
		<a href="web/registration.php" class="button">Rejestracja-załóż konto</a>
		<a href="web/reset.php" class="button">Nie pamiętam hasła</a>
	</div>
		<div class="centreWeb">
			<form action="web/login.php" method="post">
				Login:<br /><input type="text" name="login" required class = "box" /> <br />
				Hasło:<br /><input type="password" name="password" required class = "box" /> <br /><br />
				<input type="submit" value="Zaloguj się" />
			</form>
			<?php
				if(isset($_SESSION['error']))
					echo $_SESSION['error'];
					unset($_SESSION['error']);
			?>
		</div>
</div>


<?php
	echo $myPage->end();
?>
