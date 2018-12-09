<?php
  session_start();

	if(!isset($_SESSION['reset'])){
		header('Location: ../web/reset.php');
		exit();
	}


		require_once "../php/page.php";
		$DESCRIPTION = "Nowoczesny bank-wiadomość";
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
					echo $_SESSION['reset'];
				?>
			</div>
			<p><a href="../web/logout.php" class="button" >Powrót do logowania!</a></p>
	</div>



<?php
	echo $myPage->end();
?>
