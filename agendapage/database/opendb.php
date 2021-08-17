<?php

// $dbaselink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$dbaselink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Niet mogelijk om verbinding te maken met de dbase server". mysqli_connect_error());

set_time_limit(60);

?>