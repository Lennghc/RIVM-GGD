<?php
/*******************************************
*  index.php
*  Lenn van Esveld && Tygo Houweling && Robbie Makkink
*  14-06-2021
*  (C)
*******************************************/

include("../../agendapage/database/config.php");
include("../../agendapage/database/opendb.php");
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

$textForHtml = "";
$counterPerSlide = 0;
$totalCards = 0;

$query  = "SELECT * ";
$query .= "FROM vaccinations ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		echo "Geen vaccinatie's gevonden";
	} else {

		while($row = $result->fetch_assoc()) {
            
            if ($counterPerSlide == 0) {
                $textForHtml .= '<div class="carousel-item p-5 text-center"><div class="card-deck">';
            } else {
                if ($counterPerSlide == 3) {
                    $textForHtml .= '</div></div>';
                    $textForHtml .= '<div class="carousel-item p-5 text-center"><div class="card-deck">';
                    $counterPerSlide = 0;
                }
            }

            $textForHtml .= '<div class="card">
            <div class="card-body"><p class="card-text">';
            $textForHtml .= "<strong>Productnaam: </strong>" . $row['productname'] . "<br>";
            $textForHtml .= "<strong>Leverancier: </strong>" . $row['manufacturer'] . "<br>";
            $textForHtml .= "</p>";
            $textForHtml .= '<a href="updateform.php?id=' . $row['id'] . '" class="btn btn-sm btn-rivm mr-1">Wijziging</a>';
            $textForHtml .= '<a href="deleteconfirm.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger ml-1">Verwijder</a>';
            $textForHtml .= '</div></div>';

            //Our counters to make sure we draw only 3 location cards per slide
            $counterPerSlide++;
            $totalCards++;

            //We need this to close the last div or the sliding wont work correctly
            if ($totalCards == $result->num_rows) {
                $textForHtml .= '</div>';
            }
  		};
  	}
}

//

$preparedquery->close();

include("../../agendapage/database/dbclose.php");

?>
<!DOCTYPE html>
<html lang="en">

<html>

    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Vaccinatiepagina";
            include("../../include/view/admin/head.php");
        ?>
    </head>

    <body>

        <!-- This is our navbar -->
        <?php include("../../include/view/admin/navbar.php"); ?>

        <!-- Main content -->

        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">
            <div class="container">
                <?php
                    if(!isset($_SESSION)){
                        session_start();
                    }

                    if(!empty($_SESSION['message'])) {

                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                ?>

                <h5 class='text-center'>Huidige vaccinaties</h5>

                <hr class="pb-3">

                <div id="carouselSkills" class="carousel slide bg-skills rounded mt-4" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php echo $textForHtml; ?>
                    </div>

                    <a class="carousel-control-prev" style="width: unset;" href="#carouselSkills" role="button" data-slide="prev">

                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>

                    </a>

                    <a class="carousel-control-next" style="width: unset;" href="#carouselSkills" role="button" data-slide="next">

                        <span class="carousel-control-next-icon" aria-hidden="true"></span>

                    </a>

                </div>

                <!-- <div class="row text-center">
                    <div class="col">
                        <a class="btn btn-sm btn-rivm shadow-sm" href="#carouselSkills" data-slide="prev" role="button">Vorige</a>
                    </div>
                    <div class="col">
                        <a class="btn btn-sm btn-rivm shadow-sm" href="#carouselSkills" data-slide="next" role="button">Volgende</a>
                    </div>
                </div> -->
            </div>
        </section>

        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">
            <div class="container">
                <div class="col-sm-12 mb-10">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Vaccinatie toevoegen</h5>
                            <p class="card-text">Voeg hier een nieuwe vaccinatie toe.</p>
                            <button type="button" class="btn btn-rivm" data-toggle="modal" data-target="#coronaModal"><a class="btn btn-rivm" href="addform.php">Nieuwe vaccinatie toevoegen</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="p-3 mt-3" style="position: absolute; width: 100%; heigth: 60px; background-color: #fdf6bb;">
            <div class="container text-center">
                <p>© ALLE RECHTEN VOORBEHOUDEN</p>
            </div>
        </section>

        <script>
            $('.carousel-item').first().addClass('active');
            $('#carousel').carousel();
        </script>
</body>
</html>