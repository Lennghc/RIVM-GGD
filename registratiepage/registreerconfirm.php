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

if (!(isset($_POST['email'])) OR !(isset($_POST['password']))){
} else {

    mysqli_autocommit($dbaselink, FALSE);

    //////MAX ID/////

    $query = "SELECT max(id) AS maxid ";
    $query .= "FROM users ";

    $preparedquary = $dbaselink->prepare($query);
    $preparedquary->execute();

    if ($preparedquary->errno) {

        $_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
        $preparedquary->close();
        mysqli_rollback($dbaselink);
        include("../database/closedb.php");
        header("Location: registreer.php");
        exit;

    } else {
        $result = $preparedquary->get_result();

        if ($result->num_rows === 0) {
            $maxid = 0;
        } else {
            $row = $result->fetch_assoc();
            $maxid = $row['maxid'];
        }
    }
    $preparedquary->close();

    //Declare our vars
    $nextid = $maxid + 1;
    $mail = $_POST['email'];
    $password = encrypt($_POST['password']);
    $rankingLevel = 1;

    //Email pattern

    $pattern = "/^[0-9A-Za-z\.]+@[0-9A-Za-z\.]+\.[A-Za-z]{2,3}$/";
    $mailResult = preg_match($pattern, $mail);

    if(!$mailResult){
        $_SESSION['message'] = alertMsg('Geen geldige email meegegeven', null, "danger");
        mysqli_rollback($dbaselink);
        header("Location: registreer.php");
        exit;
    }

    //////Check if email exists/////

    $query = "SELECT email ";
    $query .= "FROM users ";
    $query .= "WHERE email = ? ";

    $preparedquary = $dbaselink->prepare($query);
    $preparedquary->bind_param("s", $mail);
    $preparedquary->execute();

    if ($preparedquary->errno){

        $_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
        $preparedquary->close();
        mysqli_rollback($dbaselink);
        include("../database/closedb.php");
        header("Location: registreer.php");
        exit;
        
    } else {
        $result = $preparedquary->get_result();

        //When the email is not in our database, we can add it and only then
        if($result->num_rows === 0){

            //////Insert into database/////

            $query = "INSERT INTO users ";
            $query .= "(id,email,passwords,ranking) ";
            $query .= "VALUES (?,?,?,?) ";
        
            $preparedquary = $dbaselink->prepare($query);
            $preparedquary->bind_param("issi", $nextid, $mail, $password, $rankingLevel);
            $result = $preparedquary->execute();
        
            if ( ($result===false) || ($preparedquary->errno) ){

                $_SESSION['message'] = alertMsg('Er was een fout bij het ophalen van de database', null, "danger");
                $preparedquary->close();
                mysqli_rollback($dbaselink);
                include("../database/closedb.php");
                header("Location: registreer.php");
                exit;
                
            } else {
                $preparedquary->close();
                mysqli_commit($dbaselink);
                include("../database/closedb.php");
                header("Location: registreer2.php?id=".$nextid);
                exit;
            }
        
            $preparedquary->close();
            mysqli_commit($dbaselink);

        } else {
            $_SESSION['message'] = alertMsg('Dit e-mailadres heeft al een gebruiker. Wilt u inloggen? klik dan klik dan <a href="/loginpage/login.php">hier</a>', null, "danger");
            $preparedquary->close();
            mysqli_rollback($dbaselink);
            include("../database/closedb.php");
            header("Location: registreer.php");
            exit;
        }
    }

    $preparedquery->close();
    include("../database/closedb.php");
}

?>