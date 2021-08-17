<?php
/*******************************************
*  index.php
*  Lenn van Esveld && Tygo Houweling && Robbie Makkink
*  14-06-2021
*  (C)
*******************************************/

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
                <div class="col-sm-12 mb-10">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Vaccinatie toevoegen</h5>

                            <form method="post" action="addresult.php">
                                <div class="form-group">
                                    <label for="inputAddress">Productnaam</label>
                                    <input type="text" class="form-control" name="productname" required id="inputproductname">
                                </div>
                                
                                <div class="form-group">
                                    <label for="inputAddress">Leverancier</label>
                                    <input type="text" class="form-control" name="manufacturer" required id="inputmanufacturer">
                                </div>
                                <input type="submit" class="btn btn-rivm" data-toggle="modal" data-target="#coronaModal" value="Nieuwe vaccinatie toevoegen">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-3 mt-3" style="background-color: #fdf6bb;">
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