<?php
  session_start();

	if(!isset($_SESSION['signIn'])){
		header('Location: ../index.php');
		exit();
	}

	require_once "../php/connect.php";
	$connect = DataBaseConnection::getInstance();
	$status = $_GET['status'];
	$array = $connect->getRemittance($_SESSION['userId'], $status);

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


	<a href="history.php?status=1" class="button">Oczekujące</a>
	<a href="history.php?status=2" class="button">Zrealizowane</a>
	<a href="history.php?status=3" class="button">Odrzucone</a>

	<br />
	<br />


	<div class="descriptionWeb">Historia przelewów!: </div>

	<div class="tableH">
		<table>
		   <thead>
		      <tr>
		         <th>Nazwa odbiorcy</th> <th>Konto odbiorcy</th> <th>Wartość</th> <th>Tytuł</th> <th>Data</th> <th>Status</th>
		      </tr>
		   </thead>
		   <tbody>
				 	<?php
					foreach($array as $row) {
						echo '<tr>';
						for ($i = 1; $i < count($row); $i++){
			  			echo '<th>'.$row[$i].'</th> ';
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
