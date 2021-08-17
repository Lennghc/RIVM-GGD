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


//And that the productname or manufacturer starts with uppercase letter

$productname = ucfirst( strtolower($productname) );
$manufacturer = ucfirst( strtolower($manufacturer) );

mysqli_autocommit($dbaselink, FALSE);

$query ="SELECT id ";
$query .="FROM vaccinations ";
$query .="WHERE productname = ? AND manufacturer = ? ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("ss", $productname, $manufacturer);
$preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
    $_SESSION['message'] = alertMsg('Vaccinatie kon niet worden bewerkt, probeer het later nog een keer.', null, "danger");
    $preparedquery->close();
    mysqli_commit($dbaselink);
    include("../../database/closedb.php");
    header("Location: updateform.php");
} else {
    $result = $preparedquery->get_result();

    if($result->num_rows === 0){
        echo "Nieuwe vaccinatie wordt toegevoegd<br>";
    } else {
        $row = $result->fetch_assoc();

        $_SESSION['message'] = alertMsg('Deze vaccinatie bestaat al. Wilt u deze wijziging? klik dan <a href="updateform.php?id=' . $row['id'] . '">hier</a>', null, "danger");
        $preparedquery->close();
        mysqli_commit($dbaselink);
        include("../../database/closedb.php");
        header("Location: addform.php");
        exit;
    }
}

$preparedquery->close();

$query ="SELECT max(id) AS maxid ";
$query .="FROM vaccinations ";

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


$query = "INSERT INTO vaccinations ";
$query .= "VALUES (?, ?, ?) ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("iss", $id, $productname, $manufacturer);
$result = $preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
    echo "Oops, fout";
} else {
    $_SESSION['message'] = alertMsg('Vaccinatie is toegevoegd', null, "success");
    header("Location: index.php");
}
$preparedquery->close();
mysqli_commit($dbaselink);

include("../../database/closedb.php");


?>