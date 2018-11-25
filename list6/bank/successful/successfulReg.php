<?php
  session_start();

	if(!isset($_SESSION['fullname'])){
		header('Location: ../web/registration.php');
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

	<div class="mainContainer"><main>
		<div class="logo">Nowoczesny Bank!</div>

			<div class="welcome">
				<?php
					echo "<p>Witaj ".$_SESSION['fullname'].'</p>';
					echo "<p>Udało ci się zarejestrować do najlepszego banku'</p>";
				?>
			</div>
			<p><a href="../web/logout.php" class="button" >Powrót do logowania!</a></p>
	</div>



<?php
	echo $myPage->end();
?>
