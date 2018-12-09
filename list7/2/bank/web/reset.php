<?php

	session_start();
	require_once "../php/webFunction.php";

	if(isset($_POST['login'])){
		$error = false;

		$login = $_POST['login'];
		$email = $_POST['email'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$pesel = $_POST['pesel'];

		$error = false;

		if(!preg_match('/^[0-9]+$/' , $pesel)){
			$error = true;
			$_SESSION['errorPesel'] = "Numer pesel może składać się tylko z cyfr.";
		}
		else if(!preg_match('/^[0-9]{11}$/' , $pesel)){
			$error = true;
			$_SESSION['errorPesel'] = "Numer pesel musi posiadać 11 cyfr.";
			$_SESSION['savePesel'] = $pesel;
		}
		else{
			$_SESSION['savePesel'] = $pesel;
		}

		if(!preg_match('/^[a-zA-Z0-9]*$/' , $login)){
			$error = true;
			$_SESSION['errorLogin'] = "Login nie może zawierać polskich znaków oraz specjalnych!";
		}
		else if(!preg_match('/^[a-zA-Z0-9]{3,15}$/' , $login)){
			$error = true;
			$_SESSION['errorLogin'] = "Login musi posiadać od 3-15 znaków.";
		}
		else{
			$_SESSION['saveLogin'] = $login;
		}

		$emailBool = filter_var($email, FILTER_VALIDATE_EMAIL);
		if(!$emailBool){
			$error = true;
			$_SESSION['errorEmail'] = "Niepoprawny adress email.";
		}
		else{
			$_SESSION['saveEmail'] = $email;
		}

		if(strlen($password1)<8 || strlen($password1)>50){
			$error = true;
			$_SESSION['errorPassword'] = "Hasło musi mieć conajmnie 8 znaków.";
		}
		else if($password1 != $password2){
			$error = true;
			$_SESSION['errorPassword'] = "Hasła muszą być identyczne.";
		}


		require_once "../php/connect.php";
		$connect = DataBaseConnection::getInstance();

		if(!$error){
			$result = $connect->changePassword($login, $password1, $pesel, $email);

			if($result){
				$_SESSION['reset'] = "Hasło zostało zmienione poprawnie.";
			}
			else {
				$_SESSION['reset'] = "Hasło nie zostało zmienione podano błędne dane!!!.";
			}

			header('Location: ../successful/successfulReset.php');
		}
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
	<div class="logo">Nowoczesny Bank!</div>
	<div class="menu">
		<a href="../index.php" class="button">Logowanie</a></p>
	</div>

	<div class="descriptionWeb">Resetowanie hasła: </div>


	<div class="centreWeb">
		<form  method="post">
			Login:<br /><input type="text" name="login" required class = "box" value="<?php saveValue('saveLogin') ?>" /> <br />
			<?php printError('errorLogin') ?>
			E-mail: <br /> <input type="text" name="email" required class = "box" value="<?php saveValue('saveEmail') ?>" /><br />
			<?php printError('errorEmail') ?>
			Pesel: <br /> <input type="text" name="pesel" required class = "box" value="<?php saveValue('savePesel') ?>" /><br />
			<?php printError('errorPesel') ?>
			Nowe hasło: <br /><input type="password" name="password1"  required class = "box" /> <br />
			<?php printError('errorPassword') ?>
			Powtórz hasło: <br /><input type="password" name="password2" required class = "box" /> <br /><br />
			<input type="submit" value="Reset hasła" />
		</form>
	</div>

</div>

<?php
	echo $myPage->end();
?>
