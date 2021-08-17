<?php
// File: details.php
// Author: Stan de Rijk
// Copyright Stan de Rijk
//
// Revisie History 
//
// Version 0.1 Developing Date Function 
// Version 0.2 First stage off the query
// Version 0.3 Security query
// Version 0.4 Further Developing of the query
// Version 0.5 Added the chosen vaccin to the shown data
// Version 1.0 First functiol version of the details.php

    include("../../functions/alertMsg.php");

    if(!isset($_SESSION)){
        session_start();
    }

    if( !isset($_SESSION['userid']) ){

        $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!!', null, "danger");
        header("Location: /index.php");
        exit;
    } else {
        if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] >= 2)) ){
            $_SESSION['message'] = alertMsg('U heeft niet de juiste role', null, "warning");
            header("Location: /index.php");
            exit;
        }
    }

    include("../../database/config.php");
    include("../../database/opendb.php");

    $textForHtml = "";
    $page = 1;

    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }

    switch ($page) {
        case 2:
            $day = ["Morgen", DATE("Y-m-d",strtotime(DATE("Y-m-d")."+ 1 days"))];//Tomorrow
            break;

        case 3:
            $day = ["Overmorgen", DATE("Y-m-d",strtotime(DATE("Y-m-d")."+ 2 days"))];//theDayAfter
            break;

        case 1:
        default:
            $day = ["Vandaag", DATE("Y-m-d")]; //Today.
            break;
    }
        
    $query = "SELECT users.firstname, users.lastname, users.bsn, bookings.timeslot, bookings.vaccination, bookings.id, vaccinations.productname ";
    $query .= "FROM bookings, users, vaccinations ";
    $query .= "WHERE bookings.date = ? ";
    $query .= "AND bookings.userID = users.id ";
    $query .= "AND vaccinations.id = bookings.vaccination ";
    $query .= "AND bookings.done != 1"; //Checks if the appointment is already done
    
    $preparedquery = $dbaselink->prepare($query);
    $preparedquery->bind_param("s",$day[1]);
    $preparedquery->execute();

    if ($preparedquery->errno) {
        echo "Fout bij uitvoeren commando";
    } else {
        $result = $preparedquery->get_result();

        if ($result->num_rows === 0) {
                $counterReservation = "<h5 class='text-center'> Er zijn geen reserveringen voor " . $day[0] . " </h5>";
        } else {

            $counterReservation = 0;

            for ($k = 0; $k < $result->num_rows; $k++) {
                $counterReservation++;
            }

            if ($counterReservation == 1) {
                $counterReservation = "<h5 class='text-center'> Er is " . $counterReservation . " reservering voor " . $day[0] . " </h5>";
            } elseif ($counterReservation > 1) {
                $counterReservation = "<h5 class='text-center'> Er zijn " . $counterReservation . " reserveringen voor " . $day[0] . " </h5>";
            }
            
            $counter = 0;

            while ($row = $result->fetch_assoc()) {
                $counter++;
                $textForHtml .= '<div class="col-sm"><div class="card mr-5 shadow-sm" style="width: 18rem;">
                <div class="card-body"><p class="card-text">';
                $textForHtml .= "Voornaam: " . $row['firstname'] . "<br>";
                $textForHtml .= "Achternaam: " . $row['lastname'] . "<br>";
                $textForHtml .= "BSN: " . $row['bsn'] . "<br>";
                $textForHtml .= "Vaccinatie: " . $row['productname'] . "<br>";
                $textForHtml .= "Tijd: " . $row['timeslot'] . "<br><br>";
                $textForHtml .= "</p>";
                $textForHtml .= '<a href="updateresult.php?id=' . $row['id'] . '" class="btn btn-rivm">Afspraak Klaar</a></div></div></div>';

                if ($counter == 3) {
                    $textForHtml .= '<hr class="my-4">';
                }
            }
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
            $pageTitle = "Reserveringspagina";
            include("../../include/view/admin/head.php");
        ?>
    </head>
    <body>

        <!-- This is our navbar -->
        <?php include("../../include/view/admin/navbar.php"); ?>
        
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
                <?php 
                    echo $counterReservation;
                ?>

                <hr class="pb-3">
                
                <div class="row text-center pb-5">
                    <div class="col-sm-4">
                        <a class="btn btn-rivm shadow-sm" href="index.php?page=1" role="button">Vandaag</a>
                    </div>
                    <div class="col-sm-4">
                        <a class="btn btn-rivm shadow-sm" href="index.php?page=2" role="button">Morgen</a>
                    </div>
                    <div class="col-sm-4">
                        <a class="btn btn-rivm shadow-sm" href="index.php?page=3" role="button">Overmorgen</a>
                    </div>
                </div>

            <div class="row">
                <?php echo $textForHtml; ?>
            </div>
            </div>
        </section>

        <section class="p-3 mt-3" style="position: absolute; bottom: 0; width: 100%; heigth: 60px; background-color: #fdf6bb;">
            <div class="container text-center">
                <p>© ALLE RECHTEN VOORBEHOUDEN</p>
            </div>
        </section>
        
    </body>
</html>
<?php
?>