<?php

/*

    Robbie Makkink

    14-06-21

    Name: Index.php

    Usecase: Adminpanel for RIVM

*/



include("../functions/alertMsg.php");



if(!isset($_SESSION)){

    session_start();

}



if( !isset($_SESSION['userid']) ){



    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!!', null, "danger");

    header("Location: /index.php");

    exit;

} else {

    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] >= 2)) ){

        $_SESSION['message'] = alertMsg('U heeft niet de juiste role', null, "danger");

        header("Location: /index.php");

        exit;

    }

}



//Only 'way' to edit the links for this page. Please keep it like that.



?>



<!DOCTYPE html>



<html>



    <head>

        <?php 

            //We define our header here. You can still add page to page specific stylesheets or scripts here

            //Use $title to adjust the title seen on top browser bar

            $pageTitle = "Adminspagina";

            include("../include/view/admin/head.php");

        ?>

    </head>



    <body>



        <!-- This is our navbar -->

        <?php include("../include/view/admin/navbar.php"); ?>



        <!-- Main content -->



        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">



            <div class="container">



                <?php

                    if(!empty($_SESSION['message'])) {

                        echo $_SESSION['message'];

                        unset($_SESSION['message']);

                    }

                ?>      



                    <div class="row">



                        <?php $result = isset($_SESSION['ranking']) ? ["Locatie overzicht", "locationpage/"] : ["Vaccinatie overzicht", "vaccinpage/vaccins/overview.php"]; ?>





                        <a class="col-6 register-link link--arrowed" href="<?php echo $result[1]; ?>">



                            <div class="row p-2">



                                <div class="col-10">



                                    <h2 class="d-none d-sm-block"><?php echo $result[0]; ?></h2>



                                    <h5 class="d-block d-sm-none"><?php echo $result[0]; ?></h5>



                                </div>



                                <div class="col-2">



                                    <svg class="arrow-icon d-none d-sm-block" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 32 32">



                                        <g fill="none" stroke="#fdf6bb" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">



                                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>



                                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>



                                        </g>



                                    </svg>



                                </div>





                            </div>



                        </a>





                        <?php $result = isset($_SESSION['ranking']) ? ["Reservering overzicht", "reservepage"] : ["Gebruikers overzicht", "userpage/overvieuw.php"]; ?>





                        <a class="col-6 register-link link--arrowed" href="<?php echo $result[1]; ?>">



                            <div class="row p-2">



                                <div class="col-10">



                                    <h2 class="d-none d-sm-block"><?php echo $result[0]; ?></h2>



                                    <h5 class="d-block d-sm-none"><?php echo $result[0]; ?></h5>



                                </div>



                                <div class="col-2">



                                    <svg class="arrow-icon d-none d-sm-block" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 32 32">



                                        <g fill="none" stroke="#fdf6bb" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">



                                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>



                                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>



                                        </g>



                                    </svg>



                                </div>







                            </div>



                        </a>



                    </div>



            </div>



        </section>



        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">

            <div class="container">

                <h5 class="text-center">Zoek op BSN</h5><hr>

                <form action="checkpage/index.php" method="post">

                        <div class="input-group justify-content-center">

                            <input type="number" class="form-control rounded mr-3" placeholder="Zoeken" name="searchvalue" style="max-width:25%;" required><br>

                            <input type="submit" class="btn btn-rivm" value="Zoek">

                    </div>

                </form> 

            </div>

        </section>



        <section class="p-3 mt-3" style="position: absolute; bottom: 0; width: 100%; heigth: 60px; background-color: #fdf6bb;">



            <div class="container text-center">



                <p>© ALLE RECHTEN VOORBEHOUDEN</p>



            </div>



        </section>


    </body>



</html>