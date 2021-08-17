<?php
/* 
13-06-2021
(C) Tygo Houweling
*/
include("../../functions/session.php");
include("../../functions/alertMsg.php");

//Make sure the session is running
if(!isset($_SESSION)){
    session_start();
}

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

if ( (empty($_GET['id'])) || !(isset($_GET['id'])) || !(is_numeric($_GET['id'])) ){
    $_SESSION['message'] = alertMsg('Error: Geen id meegegeven!', null, "danger");
    header("Location: index.php");
    exit;
} else {
    $id = $_GET['id'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Verwijder gebruiker";
            include("../../include/view/admin/head.php");
        ?>
    </head>

    <body>

        <!-- This is our navbar -->
        <?php include("../../include/view/admin/navbar.php"); ?>

        <!-- Main content -->

        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">
            <div class="container">
                <div class="text-center bg-skills rounded p-5">
                    <h5>Weet u zeker dat u deze gebruiker wilt verwijderen?</h5>
                    <a class="btn btn-sm btn-danger mb-2 mt-4" href="deleteresult.php?id=<?php echo $id?>">Ja, ik wil verwijderen</a><br>
                    <a class="btn btn-sm btn-success" href="index.php">Nee, ik wil NIET verwijderen</a><br>
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