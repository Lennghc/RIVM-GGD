<?php
// creator: Tygo Houweling

// date: 13-5-2021

include("../database/config.php");
include("../database/opendb.php");
include("../functions/alertMsg.php");
include("../functions/encrypt.php");

if(!isset($_SESSION)){
    session_start();
}

//Redirect user if already loggedin
if( isset($_SESSION['userid']) ){
    header("Location: /index.php");
    exit;
}

//Declare our session- vars

$mail = $_POST['email'];
$password = encrypt($_POST['password']);

//Check if the mail format is correct

$pattern = "/^[0-9A-Za-z\.]+@[0-9A-Za-z\.]+\.[A-Za-z]{2,3}$/";
$mailResult = preg_match($pattern, $mail);

if(!$mailResult){
    $_SESSION['message'] = alertMsg('Geen geldige email meegegeven', null, "danger");
    header("Location: login.php");
    exit;
}

if (!(isset($_POST['email'])) OR !(isset($_POST['password']))){
} else{

    $query="SELECT id, passwords, ranking, firstname, lastname, bsn, birthdate ";
    $query.="FROM users ";
    $query.="WHERE email=? ";
    // $query.="AND passwords=? ";

    $preparedquery=$dbaselink->prepare($query);
    $preparedquery->bind_param("s", $mail);
    $preparedquery->execute();

    if ($preparedquery->errno){

        $_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
        $preparedquery->close();
        include("../database/closedb.php");
        header("Location: login.php");
        exit;

    } else{

        $result=$preparedquery->get_result();

        if ($result->num_rows===0){

            $_SESSION['message'] = alertMsg('Deze email bestaat niet in onze gegevens. Wilt u registreren? klik dan <a href="/registratiepage/registreer.php">hier</a>', null, "danger");
            $preparedquery->close();
            include("../database/closedb.php");
            header("Location: login.php");
            exit;

        } else {

            while($row=$result->fetch_assoc()){

                if ( $row['passwords'] === $password ){

                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['ranking'] = $row['ranking'];

                    if (($row['firstname']=="")||($row['lastname']=="")||($row['bsn']=="")||($row['birthdate']=="")){

                        $_SESSION['message'] = alertMsg('Deze gegevens zijn nog niet ingevuld.', null, "danger");
                        $preparedquery->close();
                        include("../database/closedb.php");
                        header("Location: ../registratiepage/registreer2.php");
                        exit;
    
                    } else {

                        $_SESSION['message'] = alertMsg('Welkom terug, ' . $row['firstname'] . ' ' . $row['lastname'], null, "success");
                        $preparedquery->close();
                        include("../database/closedb.php");
                        header('Location: /index.php');
                        exit;
                    }

                } else {

                    $_SESSION['message'] = alertMsg('Uw wachtwoord is onjuist. Probeer nogmaals', null, "danger");
                    $preparedquery->close();
                    include("../database/closedb.php");
                    header("Location: login.php");
                    exit;
                    
                }
            };

        }

    }

    $preparedquery->close();
    include("../database/closedb.php");
}



?>