<?php
  session_start();
	require_once "../php/webFunction.php";

	if(isset($_POST['login'])){
		$error = false;

		$login = $_POST['login'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$pesel = $_POST['pesel'];

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

		if(!preg_match('/^[A-Z][a-ząćęłńóśźżł]+$/' , $name)){
			$error = true;
			$_SESSION['errorName'] = "Niepoprawne imię.";
		}
		else{
			$_SESSION['saveName'] = $name;
		}

		if(!preg_match('/^[A-Z][a-ząćęłńóśźżł]+$/' , $surname)){
			$error = true;
			$_SESSION['errorSurname'] = "Niepoprawne nazwisko.";
		}
		else{
			$_SESSION['saveSurname'] = $surname;
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

		if(!isset($_POST['checkbox1'])){
			$error = true;
			$_SESSION['errorCheckbox'] = "Rejestracja wymaga akceptacji regulaminu.";
		}


		require_once "../php/connect.php";
		$connect = DataBaseConnection::getInstance();

		if(!isset($_SESSION['errorEmail'])) {
				if($connect->isEmail($email)){
					$error = true;
					$_SESSION['errorEmail'] = "Adress email jest zajęty.";
				}
			}
			if(!isset($_SESSION['errorLogin'])){
				if($connect->isLogin($login)){
					$error = true;
					$_SESSION['errorLogin'] = "Login jest zajęty.";
				}
		}
		if(!$error){
			$fullname = $name.' '.$surname;

			$result = $connect->registration($login, $password1, $fullname, $email, $pesel);
			if($result){
				$_SESSION['fullname'] = $fullname;
				header('Location: ../successful/successfulReg.php');
			}
			else {
				echo "błąd rejestracji";
			}
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
	<div class="logo">Darmowa Rejestracja!</div>
	<div class="menu">
		<a href="../index.php" class="button">Powrót do logowania!</a>
	</div>
	<div class="centreWeb">
		<form method="post">
			Login: <br /> <input type="text" name="login" required class = "box" value="<?php saveValue('saveLogin') ?>" /><br />
			<?php printError('errorLogin') ?>
			Imię: <br /> <input type="text" name="name" required class = "box"value="<?php saveValue('saveName') ?>" /><br />
			<?php printError('errorName') ?>
			Nazwisko: <br /> <input type="text" name="surname" required class = "box" value="<?php saveValue('saveSurname') ?>" /><br />
			<?php printError('errorSurname') ?>
			Pesel: <br /> <input type="text" name="pesel" required class = "box" value="<?php saveValue('savePesel') ?>" /><br />
			<?php printError('errorPesel') ?>
			E-mail: <br /> <input type="text" name="email" required class = "box" value="<?php saveValue('saveEmail') ?>" /><br />
			<?php printError('errorEmail') ?>
			Hasło: <br /> <input type="password" required class = "box" name="password1" /><br />
			<?php printError('errorPassword') ?>
			Powtórz hasło: <br /> <input type="password" required class = "box" name="password2" /><br />

			<label>
				<input type="checkbox" name="checkbox1" /> Akceptuję regulamin
			</label>
			<?php printError('errorCheckbox') ?>
			<br />
			<br />
			<input type="submit" value="Zarejestruj się" />
		</form>
	</div>


</div>

<?php
	echo $myPage->end();
?>
