<?php
  session_start();

	if(!isset($_SESSION['signIn'])){
		header('Location: ../index.php');
		exit();
	}

	if(!isset($_SESSION['userAdmin']) || $_SESSION['userAdmin'] == 0 ){
		header('Location: main.php');
		exit();
	}

	require_once "../php/connect.php";
	$connect = DataBaseConnection::getInstance();
	$array = $connect->getAllRemittance(PayStatus::realize);

	if(!$array){
		$array[0][0] = " ";
		$array[0][1] = " ";
		$array[0][2] = " ";
		$array[0][3] = " ";
		$array[0][4] = " ";
		$array[0][5] = " ";
		$array[0][6] = " ";
		$array[0][7] = " ";
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
	<div class="logo">Panel administracyjny!</div>
	<div class="menu">
		<a href="../web/main.php" class="button">Strona główna!</a></p>
	</div>

	<div class="welcome">
		<?php
		  echo "<p>Witaj <b>".$_SESSION['userName'].'!</b></p>';
		?>
	</div>
	<div class="descriptionWeb">Historia przelewów!: </div>

	<div class="tableH">
		<table>
			 <thead>
					<tr>
						 <th>Id użytkownika</th> <th>Nazwa odbiorcy</th> <th>Konto odbiorcy</th> <th>Wartość</th> <th>Tytuł</th> <th>Data</th> <th>Status</th> <th>Akcja</th> <th>Akcja</th>
					</tr>
			 </thead>
			 <tbody>
					<?php
					foreach($array as $row) {
						echo '<input type="hidden" name = "status" value="'.$row[0].'" />';
						echo '<tr>';
						for ($i = 1; $i < count($row); $i++){
							echo '<th>'.$row[$i].'</th> ';
						}
						echo '<th><a href="payAccept.php?accept=1&remittance='.$row[0].'&userId='.$row[1].'" class="myHref">Akceptuj!</a></th> ';
						echo '<th><a href="payAccept.php?accept=0&remittance='.$row[0].'&userId='.$row[1].'" class="myHref">Odrzuć!</a></th> ';
						echo '</tr>';
					}
					?>
			 </tbody>
		 </table>
	 </div>
</div>

<?php
	echo $myPage->end();
?>
