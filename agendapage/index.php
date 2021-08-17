<?php

/*******************************************

*  index.php

*  Lenn van Esveld

*  14-06-2021

*  (C) Lennghc.nl

*******************************************/

function build_calendar($month, $year) {

    include("../database/config.php");

    include("../database/opendb.php");

    include("../functions/session.php"); 



    include("../include/sqldupeuserid.php");



    if(!isset($_SESSION['userid'])){

		$_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!', null, "danger");

		header("Location: ../index.php");

		exit;

    }



    if(isset($_GET['date'])){

        $date = $_GET['date'];

        



        $quary = "SELECT id,date,timeslot ";

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

                include("../database/closedb.php");

            

        }

        }



    }

        

     // Create array containing abbreviations of days of week.

     $daysOfWeek = array('Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag');



     // What is the first day of the month in question?

     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);



     // How many days does this month contain?

     $numberDays = date('t',$firstDayOfMonth);



     // Retrieve some information about the first day of the

     // month in question.

     $dateComponents = getdate($firstDayOfMonth);



     // What is the name of the month in question?

     $monthName = $dateComponents['month'];



     // What is the index value (0-6) of the first day of the

     // month in question.

     $dayOfWeek = $dateComponents['wday'];



     // Create the table tag opener and day headers

     

    $datetoday = date('Y-m-d');

    

    

    $calendar = "<table class='table table-bordered'>";

    $calendar .= "<center><h2>$monthName $year</h2>";

    

    $calendar .= "<a class='btn btn-ms btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'>Vorige maand</a> ";

    

    $calendar.= " <a class='btn btn-ms btn-primary' href='?month=".date('m')."&year=".date('Y')."'>Deze maand</a> ";

    

    $calendar.= "<a class='btn btn-ms btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."'>Volgende maand</a></center><br>";

    



      $calendar .= "<tr>";



     // Create the calendar headers



     foreach($daysOfWeek as $day) {

          $calendar .= "<th  class='header'>$day</th>";

     } 



     // Create the rest of the calendar



     // Initiate the day counter, starting with the 1st.



     $currentDay = 1;



     $calendar .= "</tr><tr>";



     // The variable $dayOfWeek is used to

     // ensure that the calendar

     // display consists of exactly 7 columns.



     if ($dayOfWeek > 0) { 

         for($k=0;$k<$dayOfWeek;$k++){

                $calendar .= "<td  class='empty'></td>"; 



         }

     }

    

     

     $month = str_pad($month, 2, "0", STR_PAD_LEFT);

  

     while ($currentDay <= $numberDays) {



          // Seventh column (Saturday) reached. Start a new row.



          if ($dayOfWeek == 7) {



               $dayOfWeek = 0;

               $calendar .= "</tr><tr>";



          }

          

          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

          $date = "$year-$month-$currentDayRel";

          

            $dayname = strtolower(date('l', strtotime($date)));

            $eventNum = 0;

            $today = $date==date('Y-m-d')? "today" : "";

            if($date<date('Y-m-d')){

                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-sm'>N/A</button>";

            }else{

                 $calendar.="<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=".$date."' class='btn btn-success btn-sm'>Reserveer</a>";

                 

            }

            

            

           

            

          $calendar .="</td>";

          // Increment counters

 

          $currentDay++;

          $dayOfWeek++;



     }



     // Complete the row of the last week in month, if necessary



     if ($dayOfWeek != 7) { 

     

          $remainingDays = 7 - $dayOfWeek;

            for($l=0;$l<$remainingDays;$l++){

                $calendar .= "<td class='empty'></td>"; 



         }



     }

     

     $calendar .= "</tr>";



     $calendar .= "</table>";



     echo $calendar;



}



?>



<html>



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

            <div class="row">

            

                <div class="col-md-12">

                    <?php

                        $dateComponents = getdate();

                        if(isset($_GET['month']) && isset($_GET['year'])){

                            $month = $_GET['month']; 			     

                            $year = $_GET['year'];

                        }else{

                            $month = $dateComponents['mon']; 			     

                            $year = $dateComponents['year'];

                        }

                        



                        echo build_calendar($month,$year);

                    ?>

                </div>

            </div>

        </div>



        <section class="p-3 mt-3" style="position: absolute; width: 100%; heigth: 60px; background-color: #fdf6bb;">

            <div class="container text-center">

                <p>© ALLE RECHTEN VOORBEHOUDEN</p>

            </div>

        </section>

</body>



</html>

