<?php
/*******************************************
* searchresult.php
* Result for search for item from the database
*  Lenn van Esveld
*  18-06-2021
*  (C) Lennghc.nl
*******************************************/
include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/alertMsg.php");

//Make sure the session is running
if(!isset($_SESSION)){
    session_start();
}

//Make sure the user is loggedin and has the required role

if( !isset($_SESSION['userid']) ){

    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!', null, "danger");
    header("Location: /index.php");
    exit;
} else {
    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] == 3)) ){
        $_SESSION['message'] = alertMsg('Alleen administratoren mogen dit doen.', null, "danger");
        header("Location: /adminpage");
        exit;
    }
}



$searchvalue = $_POST['searchvalue'];

$query  = "SELECT id ";
$query .= "FROM users ";
$query .= "WHERE bsn = ? ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i", $searchvalue);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		echo "Geen gegevens voor de persoon gevonden";
		exit;
	} else {
		while($row = $result->fetch_assoc()) {
			$id = $row['id'];
  		};
  	}
}
$preparedquery->close();

// search in db bookings

$textForHtml = "";

$query  = "SELECT date, timeslot, userID, done, id ";
$query .= "FROM bookings ";
$query .= "WHERE userid = ? ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i", $id);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		$textForHtml .= "<h5>Geen gegevens voor de persoon gevonden</h5>";
	} else {
		echo "<form action='updateresult.php' method=post>";
		while($row = $result->fetch_assoc()) {
			if ( $row['done'] == 0 ) {
                
                $textForHtml .= '<div class="col-sm"><div class="card mr-5 shadow-sm" style="width: 18rem;">
                <div class="card-body"><p class="card-text">';
                $textForHtml .= "Datum: " . $row['date'] . "<br>";
                $textForHtml .= "Tijd: " . $row['timeslot'] . "<br>";
                $textForHtml .= "</p>";
                $textForHtml .= "<a href=\"updateresult.php?id=" .  $row['id'] . "\" class=\"btn btn-rivm\">Afspraak Klaar</a>";
                $textForHtml .= "</div></div></div>";

            }
  		};
		  
  	}
}
$preparedquery->close();

include("../../database/closedb.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Searchpagina";
            include("../../include/view/admin/head.php");
        ?>
    </head>
    <body>

        <!-- This is our navbar -->
        <?php include("../../include/view/admin/navbar.php"); ?>
        
        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">
            <div class="container">
                <?php
                    if(!empty($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                ?>      
			<h5 class='text-center'>Reserveringen</h5>

                <hr class="pb-3">
                
            <div class="row">
                <?php echo $textForHtml; ?>
            </div>
            </div>
        </section>

        <section class="p-3 mt-3" style="background-color: #fdf6bb;">
            <div class="container text-center">
                <p>© ALLE RECHTEN VOORBEHOUDEN</p>
            </div>
        </section>
        
    </body>
</html>