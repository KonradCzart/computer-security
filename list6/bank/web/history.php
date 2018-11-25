<?php
  session_start();

	if(!isset($_SESSION['signIn'])){
		header('Location: ../index.php');
		exit();
	}

	require_once "../php/connect.php";
	$connect = DataBaseConnection::getInstance();
	$array = $connect->getRemittance($_SESSION['userId']);

	if(!$array){
		$array[0][0] = " ";
		$array[0][1] = " ";
		$array[0][2] = " ";
		$array[0][3] = " ";
		$array[0][4] = " ";
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
	<div class="descriptionWeb">Historia przelewów!: </div>

	<div class="tableH">
		<table>
		   <thead>
		      <tr>
		         <th>Nazwa odbiorcy</th> <th>Konto odbiorcy</th> <th>Wartość</th> <th>Tytuł</th> <th>Data</th>
		      </tr>
		   </thead>
		   <tbody>
				 	<?php
					foreach($array as $row) {
						echo '<tr>';
						foreach($row as $value) {
			  			echo '<th>'.$value.'</th> ';
						}
						echo '</tr>';
					}
					?>
		   </tbody>
		</table>
	</div>

	<a href="main.php" class="button">Strona główna!</a>

</div>

<?php
	echo $myPage->end();
?>
