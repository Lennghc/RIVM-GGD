<?php

/*******************************************

* overview.php

* Nigel van Maanen / Tygo Houweling

* 05-16-2021

* (C) ROC Midden Nederland

*******************************************/

include("../../database/config.php");

include("../../database/opendb.php");

include("../../functions/alertMsg.php");



if(!isset($_SESSION)){

    session_start();

}



if( !isset($_SESSION['userid']) ){



    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!!', null, "danger");

    header("Location: /index.php");

    exit;

} else {

    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] == 3)) ){

        $_SESSION['message'] = alertMsg('Alleen administratoren mogen dit doen.', null, "danger");

        header("Location: /adminpage");

        exit;

    }

}



// Check and load parameters



if ( (isset($_GET['page'])) && (!is_numeric($_GET['page'])) ){

    $_SESSION['message'] = alertMsg('Error: deze pagina bestaat niet!', null, "danger");

    header("Location: index.php");

    exit;

} else {

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;



    //Adjust the maximum page limit here 

    $perPage = 10;

    $limit = ($page > 1) ? ($page * $perPage) - $perPage : 0;

}

?>

<!DOCTYPE html>

<html>



    <head>

        <?php 

            //We define our header here. You can still add page to page specific stylesheets or scripts here

            //Use $title to adjust the title seen on top browser bar

            $pageTitle = "Gebruikerspagina";

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

                    if(!empty($_SESSION['message'])) {



                        echo $_SESSION['message'];

                        unset($_SESSION['message']);

                    }

                ?>



                <h1>Overzicht Gebruikers</h1>



                <div class="table-responsive mt-4">

                    <table class="table">

                        <thead class="thead-rivm shadow">

                            <tr>

                            <th scope="col">GebruikersID</th>

                            <th scope="col">Voornaam</th>

                            <th scope="col">Achternaam</th>

                            <th scope="col">E-mailadres</th>

                            <th scope="col">Rol</th>

                            <th scope="col">Wijzigen</th>

                            <th scope="col">Verwijderen</th>

                            </tr>

                        </thead>

                    <?php

                        $query  = "SELECT SQL_CALC_FOUND_ROWS users.id, users.firstname, users.lastname, users.email, rank.name ";

                        $query .= "FROM users, rank ";

                        $query .= "WHERE users.ranking = rank.id ";

                        $query .= "ORDER BY users.ranking DESC ";

                        $query .= "LIMIT ?, ? ";



                        $preparedquery = $dbaselink->prepare($query);

                        $preparedquery->bind_param("ii", $limit, $perPage);

                        $preparedquery->execute();



                        if ($preparedquery->errno) {

                            //echo "Fout bij uitvoeren commando";

                        } else {

                            $result = $preparedquery->get_result();



                            if($result->num_rows === 0) {

                                $_SESSION['message'] = alertMsg('Error: deze pagina bestaat niet!', null, "danger");

                                $preparedquery->close();

                                include("../../database/closedb.php");

                                header("Location: index.php");

                                exit;

                            } else {



                                $pages = ceil($result->num_rows / $perPage);



                                while($row = $result->fetch_assoc())

                                {

                                    echo "<tr>";

                                    echo "<td>" . $row['id'] . "</td>";

                                    echo "<td>" . ( empty($row['firstname']) ? '<i class="fa fa-ban" aria-hidden="true"></i>' : $row['firstname'] ) . "</td>";

                                    echo "<td>" . ( empty($row['lastname']) ? '<i class="fa fa-ban" aria-hidden="true"></i>' : $row['lastname'])  . "</td>";

                                    echo "<td>" . $row['email'] . "</td>";

                                    echo "<td>" . $row['name'] . "</td>";

                                    echo "<td><a href=\"updateform.php?id=" . $row['id'] ."\"><i class=\"fas fa-edit\" aria-hidden=\"true\"></i></a></td>";

                                    echo "<td><a href=\"deleteconfirm.php?id=" . $row['id'] . "\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></td>";

                                    echo "</tr>";

                                };

                            }

                        }

                        $preparedquery->close();



                        //Grab our maximum rows availbe



                        $query = "SELECT FOUND_ROWS() as total";



                        $preparedquery = $dbaselink->prepare($query);

                        $preparedquery->execute();



                        if ($preparedquery->errno) {

                        } else {

                            $result = $preparedquery->get_result();



                            if($result->num_rows === 0) {

                                $preparedquery->close();

                                include("../../database/closedb.php");

                                header("Location: index.php");

                                exit;

                            } else {

                                $pages = ceil($result->fetch_assoc()['total'] / $perPage);

                            }

                        }

                        $preparedquery->close();

                        include("../../database/closedb.php");

                    ?>

                </table>

            </div>

                <nav class="d-flex justify-content-center mt-4">

                    <ul class="pagination">

                        <?php

                            for($x = 1; $x <= $pages; $x++) {

                                if($page == $x){

                                    echo '<li class="page-item active"><a class="page-link" href="index.php?page='. $x .'">'. $x .'<span class="sr-only">(current)</span></a></li>';

                                }

                                else{

                                    echo '<li class="page-item"><a class="page-link" href="index.php?page='. $x .'">'. $x .'</a></li>';

                                }

                            }

                        ?>

                    </ul>

                </nav>

            </div>

        </section>



        <section class="p-3 mt-3" style="background-color: #fdf6bb;">

            <div class="container text-center">

                <p>© ALLE RECHTEN VOORBEHOUDEN</p>

            </div>

        </section>

</body>

</html>

