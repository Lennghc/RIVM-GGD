<?php
 /* 
10-06-2021
(C) Tygo Houweling
*/
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

//Define our vars

$id = $_POST['id'];
$productname = $_POST['productname'];
$manufacturer = $_POST['manufacturer'];

//Check for spaces
$pattern = ' ';
$productname = str_replace($pattern, '', $productname);
$manufacturer = str_replace($pattern, '', $manufacturer);

if( ($productname == "") || ($manufacturer == "")){
    $_SESSION['message'] = alertMsg('Formulier niet volledig ingevuld.', null, "danger");
    header("Location: addform.php");
    exit;
}

//Make sure our zipcode is in uppercase
//And that the city or adres starts with uppercase letter

$productname = ucfirst( strtolower($productname) );
$manufacturer = ucfirst( strtolower($manufacturer) );

$query = "UPDATE vaccinations ";
$query .= "SET productname = ?, ";
$query .= "manufacturer = ? ";
$query .= "WHERE id = ? ";


$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("ssi", $productname, $manufacturer, $id);
$result = $preparedquery->execute();
 
if (($result===false) || ($preparedquery->errno)) {
    $_SESSION['message'] = alertMsg('Vaccinatie kon niet worden bewerkt, probeer het later nog een keer.', null, "danger");
    $preparedquery->close();
    include("../database/closedb.php");
    header("Loaction: updateform.php");
} else {
    $_SESSION['message'] = alertMsg('Locatie is bewerkt', null, "success");
    header("Location: index.php");
}
$preparedquery->close();
 
include("../../database/closedb.php");



 
?>