<?php
/*******************************************
*  sqldupeuserid.php
*  Lenn van Esveld
*  14-06-2021
*  (C) Lennghc.nl
*******************************************/

$bsn = $_POST['bsn'];

$query = "SELECT bsn ";
$query .= "FROM users ";
$query .= "WHERE bsn = ? ";

$preparedquary = $dbaselink->prepare($query);
$preparedquary->bind_param("i", $bsn);
$preparedquary->execute();

if ($preparedquary->errno){

	$_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
	$preparedquary->close();
	include("../database/closedb.php");
	header("Location: registreer2.php?id=".$id);
	exit;
	
} else {
	$result = $preparedquary->get_result();

	//When the bsn is not in our database, we can add it and only then
	if($result->num_rows === 0){
	
		if ( ($result===false) || ($preparedquary->errno) ){

			$_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
			$preparedquary->close();
			include("../database/closedb.php");
			header("Location: registreer.php");
			exit;
			
		} 	
		$preparedquary->close();

	} else {
		$_SESSION['message'] = alertMsg('Dit bsn heeft al een gebruiker. Wilt u inloggen? klik dan klik dan <a href="/loginpage/login.php">hier</a>', null, "danger");
		$preparedquary->close();
		include("../agendapage/database/closedb.php");
		header("Location: registreer2.php?id=".$id);
		exit;
	}
}

?>