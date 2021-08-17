<!-- Tygo Houweling
13-06-2021 -->

<?php

include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/alertMsg.php");

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

$query = "SELECT * ";
$query .= "FROM locations ";
$query .= "WHERE id = ? ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i", $id);
$preparedquery->execute();


if($preparedquery->errno) {
    echo "Fout bij uitvoeren commando";
} else {
    $result = $preparedquery->get_result();
    if($result->num_rows === 0) {
        echo "Geen rijen gevonden";
    } else {
        while($row = $result->fetch_assoc()) {
            $city = $row['city'];
            $adres = $row['adres'];
            $zipcode = $row['zipcode'];
            $housenumber=$row['houseNumber'];
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
            $pageTitle = "Locatiepagina";
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
                            <h5 class="card-title">Locatie wijzigen</h5>

                            <form method="post" action="updateresult.php">
                                <input type="hidden" name="id" value=<?php echo $id;?>><br>
                                <div class="form-group">
                                    <label for="inputAddress">Straat</label>
                                    <input type="text" class="form-control" name="street" value=<?php echo $adres; ?>  required id="inputAddress">
                                </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputCity">Stad</label>
                                            <input type="text" class="form-control" value=<?php echo $city; ?> name="city" required id="inputCity">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="number">Huisnummer</label>
                                            <input type="text" class="form-control" value=<?php echo $housenumber; ?> name="number" required id="number">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputZip">Postcode</label>
                                            <input type="text" class="form-control" value=<?php echo $zipcode; ?> name="zipcode" required id="inputZip">
                                        </div>
                                        </div>
                                <input type="submit" class="btn btn-rivm" data-toggle="modal" data-target="#coronaModal" value="Locatie data wijzigen">
                                <a href="deleteconfirm.php?id=<?php echo $id;?>" class="btn btn-danger float-right ml-1">Verwijder</a>
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
</body>
</html>