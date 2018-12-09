<?php
	session_start();
	require_once "../php/webFunction.php";

	if(!isset($_SESSION['signIn'])){
		header('Location: ../index.php');
		exit();
	}

	if(isset($_POST['name'])){
		$name = $_POST['name'];
		$account = $_POST['account'];
		$amountZl = $_POST['zl'];
		$amountGr = $_POST['gr'];
		$title = $_POST['title'];

		$error = false;

		if(!preg_match('/^[a-zA-Z0-9ąćęłńóśźżł. ]+$/' , $name)){
			$error = true;
			$_SESSION['errorName'] = "Niepoprawna nazwa odbiorcy";
		}
		else{
			$_SESSION['saveName'] = $name;
		}

		if(!preg_match('/^[0-9]+$/' , $account)){
			$error = true;
			$_SESSION['errorAccount'] = "Numer konta może składać się tylko z cyfr.";
		}
		else if(!preg_match('/^[0-9]{26}$/' , $account)){
			$error = true;
			$_SESSION['errorAccount'] = "Numer konta musi posiadać 26 cyfr.";
			$_SESSION['saveAccount'] = $account;
		}
		else{
			$_SESSION['saveAccount'] = $account;
		}

		$_SESSION['saveZl'] = $amountZl;
		$_SESSION['saveGr'] = $amountGr;
		$_SESSION['saveTitle'] = $title;;

		if(!$error){
			$amount = $amountZl + $amountGr/100;
			$_SESSION['pay'] = true;
			$_SESSION['payName'] = $name;
			$_SESSION['payAccount'] = $account;
			$_SESSION['payAmount'] = $amount;
			$_SESSION['payTitle'] = $title;
			header('Location: acceptPay.php');
			exit();
		}

		if(isset($_SESSION['payResponse'])){
			unset($_SESSION['payResponse']);
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
	<div class="logo">Nowoczesny bank!</div>
	<div class="menu">
		<a href="main.php" class="button">Strona główna.</a>
	</div>
	<div class="descriptionWeb">Uzupełnij dane do przelewu</div>
	<div class="remittanceWeb">
		<form method="post" id="remittance">
			Nazwa odbiorcy: <br />
			<input type="text" name="name" size="30" required class = "box" value="<?php saveValue('saveName')  ?>" /><br />
			<?php printError('errorName') ?>
			Numer konta: <br />
			<input type="text" name="account" size="30" required class = "box" value="<?php saveValue('saveAccount') ?>" /><br />
			<?php printError('errorAccount') ?>
			Kwota: <br />
			<input type="number" name="zl" min="0" required class = "box" value="<?php saveValue('saveZl') ?>" /> zł
			<input type="number" name="gr" min="0" required class = "box" value="<?php saveValue('saveGr') ?>"  max="99"> gr <br />
			Tytuł: <br />
			<textarea rows="4" cols="50" name="title" required class = "box" form="remittance"><?php saveValue('saveTitle') ?></textarea><br />

			<input type="submit" value="Generuj" />
		</form>
	</div>
</div>

<?php
	echo $myPage->end();
?>
