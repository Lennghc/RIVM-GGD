<?php
/*******************************************
*  book.php
*  Lenn van Esveld
*  14-06-2021
*  (C) Lennghc.nl
*******************************************/

include("../functions/session.php"); 
include("../include/sqldupeuserid.php");

include("../database/config.php");
include("../database/opendb.php");


if(isset($_GET['date'])){
    $date = $_GET['date'];

    $quary = "SELECT * ";
    $quary .= "FROM bookings ";
    $quary .= "WHERE date = ? ";

    $preparedquary=$dbaselink->prepare($quary);
    $preparedquary->bind_param("s", $date);

    $bookings = array();
        if($preparedquary->execute()){
        $result = $preparedquary->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            $preparedquary->close();
        }
    }
}

if(isset($_POST['submit'])){
    $timeslot = $_POST['timeslot'];
    $location = $_POST['location'];
    $vaccination = $_POST['vaccination'];
    $userid = $_SESSION['userid'];
    
    
    
    
    $quary = "SELECT city ";
    $quary .= "FROM locations ";
    $quary .= "WHERE id = $location ";

    $preparedquary=$dbaselink->prepare($quary);
    $preparedquary->execute();


    if ($preparedquary->errno) {
        echo "Fout bij uitvoeren commando";
    } else {
        $result = $preparedquary->get_result();
    
        if($result->num_rows === 0) {
            echo "Geen locatie's gevonden";
        } else {  
          while($row = $result->fetch_assoc()) {
            $city = $row['city'];      
              }
          }
        }




    $quary = "SELECT * ";
    $quary .= "FROM bookings ";
    $quary .= "WHERE date = ? ";
    $quary .= "AND timeslot = ?";

    $preparedquary=$dbaselink->prepare($quary);
    $preparedquary->bind_param("ss", $date, $timeslot);




        if($preparedquary->execute()){
            $result = $preparedquary->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Reservering Bezet</div>";
        }else{

            $quary = "INSERT INTO bookings ";
            $quary .= "(timeslot, date, location,vaccination,userid) ";
            $quary .= "VALUES (?,?,?,?,?)";
        
            $preparedquary=$dbaselink->prepare($quary);
            $preparedquary->bind_param("sssss", $timeslot, $date, $location, $vaccination, $userid);




            $preparedquary->execute();

            
            $msg = "<div class='p-4 alert alert-success'>Reservering succesvol";
            $msg .= "<a class=\"float-sm-right\" href=\"../pdf/index.php\" >Print reservering</a></div>";
            $msg .= "<div class='col-3 alert alert-success'><a  href=\"../index.php\">Terug naar de homepage</a></div>"; 
            $bookings[] = $timeslot;

            


        }
    }
}

include("../database/closedb.php");

$duration = 10;
$cleanup = 0;
$start = "06:00";
$end = "23:00";


function timeslots($duration, $cleanup, $start, $end){
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots = array();
    
    for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if($endPeriod>$end){
            break;
        }
        
        $slots[] = $intStart->format("H:iA")." - ". $endPeriod->format("H:iA");
        
    }
    
    return $slots;
}

?>
<!doctype html>
<html lang="en">

    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Reseveerpagina";
            include("../include/view/user/head.php");
        ?>
        <link href="style.css" type="text/css" rel="stylesheet"/>
    </head>

    <body>
        <!-- This is our navbar -->
        <?php include("../include/view/user/navbar.php"); ?>

        <div class="container">
            <h1 class="text-center pt-4">Reserveer jouw datum: <?php echo date("jS F, Y", strtotime($date)); ?></h1><hr>
            
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo(isset($msg))?$msg:""; ?>
                    </div>

                    <?php $timeslots = timeslots($duration, $cleanup, $start, $end); 
                        foreach($timeslots as $ts){
                    ?>
                    <div class="col-md-2">
                        <div class="form-group">
                    <?php if(in_array($ts, $bookings)){ ?>
                        <button class="btn btn-danger btn-sm"><?php echo $ts; ?></button>
                    <?php }else{ ?>
                        <button class="btn btn-success book btn-sm" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                    <?php }  ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>


        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reserveer voor: <span id="slot"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Tijdslot</label>
                                        <input readonly type="text" class="form-control" id="timeslot" name="timeslot">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Locatie</label>
                                        <?php
                                            include("../include/sqlselectlocations.php");
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Vaccinatie</label>
                                        <?php
                                            include("../include/sqlselectvaccination.php");
                                        ?>
                                    </div>
                                    <div class="form-group pull-right">
                                        <button name="submit" type="submit" class="btn btn-primary">Verzenden</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
        <section class="p-3 mt-3" style="position: absolute; width: 100%; heigth: 60px; background-color: #fdf6bb;">
            <div class="container text-center">
                <p>© ALLE RECHTEN VOORBEHOUDEN</p>
            </div>
        </section>

        <script>
            $(".book").click(function(){
                var timeslot = $(this).attr('data-timeslot');
                $("#slot").html(timeslot);
                $("#timeslot").val(timeslot);
                $("#myModal").modal("show");
            });
        </script>
  
    </body>

</html>
