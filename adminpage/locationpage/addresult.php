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

$city = $_POST['city'];
$adres = $_POST['street'];
$zipcode = $_POST['zipcode'];
$number = $_POST['number'];

//Check for spaces
$pattern = ' ';
$city = str_replace($pattern, '', $city);
$adres = str_replace($pattern, '', $adres);
$zipcode = str_replace($pattern, '', $zipcode);
$number = str_replace($pattern, '', $number);

if( ($city == "") || ($adres == "") || ($zipcode == "") || ($number == "") ){
    $_SESSION['message'] = alertMsg('Formulier niet volledig ingevuld.', null, "danger");
    header("Location: addform.php");
    exit;
}


//Make sure our zipcode is in uppercase
//And that the city or adres starts with uppercase letter

$city = ucfirst( strtolower($city) );
$adres = ucfirst( strtolower($adres) );
$zipcode = strtoupper($zipcode);

//Check the zipcode against our preg

$pattern = "/^[1-9][0-9]{3} ?[A-Za-z]{2}$/";
$result = preg_match($pattern, $zipcode);

if (!$result){
    $_SESSION['message'] = alertMsg('Ongeldige postcode!', null, "danger");
    header("Location: addform.php");
    exit;
}

$pattern="/ /";
$zipcode = preg_replace($pattern,"",$zipcode);

mysqli_autocommit($dbaselink, FALSE);

$query ="SELECT id ";
$query .="FROM locations ";
$query .="WHERE city = ? AND adres = ? AND zipcode = ?";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("sss", $city, $adres, $zipcode);
$preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
    $_SESSION['message'] = alertMsg('Locatie kon niet worden bewerkt, probeer het later nog een keer.', null, "danger");
    $preparedquery->close();
    mysqli_commit($dbaselink);
    include("../../database/closedb.php");
    header("Location: updateform.php");
} else {
    $result = $preparedquery->get_result();

    if($result->num_rows === 0){
        echo "Nieuwe locatie wordt toegevoegd<br>";
    } else {
        $row = $result->fetch_assoc();

        $_SESSION['message'] = alertMsg('Deze locatie bestaat al. Wilt u deze wijziging? klik dan <a href="updateform.php?id=' . $row['id'] . '">hier</a>', null, "danger");
        $preparedquery->close();
        mysqli_commit($dbaselink);
        include("../../database/closedb.php");
        header("Location: addform.php");
        exit;
    }
}

$preparedquery->close();

$query ="SELECT max(id) AS maxid ";
$query .="FROM locations ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
    mysqli_rollback($dbaselink);
} else {
    $result = $preparedquery->get_result();

    if($result->num_rows === 0){
        $maxid = 0;

    } else {
        $row = $result->fetch_assoc();
        $maxid = $row['maxid'];
    }
}
$preparedquery->close();


$id = $maxid + 1;


$query = "INSERT INTO locations ";
$query .= "VALUES (?, ?, ?, ?, ?) ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("issss", $id, $city, $adres, $zipcode,$number);
$result = $preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
    echo "Oops, fout";
} else {
    $_SESSION['message'] = alertMsg('Locatie is toegevoegd', null, "success");
    header("Location: index.php");
}
$preparedquery->close();
mysqli_commit($dbaselink);

include("../../Database/closedb.php");


?>