<?php
/*
    Robbie Makkink
    14-06-21
    Name: Index.php
    Usecase: Homefile for RIVM
*/
include("database/config.php");
include("database/opendb.php");

if(!isset($_SESSION)){
    session_start();
}

if( isset($_SESSION['userid']) ){
    $id = $_SESSION['userid'];
}

if ( isset($id) )
{
    $query = "SELECT bookings.timeslot, bookings.vaccination, bookings.id, bookings.date, vaccinations.productname, vaccinations.manufacturer, locations.city, locations.adres, locations.zipcode, locations.houseNumber ";
    $query .= "FROM bookings, locations, vaccinations ";
    $query .= "WHERE bookings.userID = ? ";
    $query .= "AND vaccinations.id = bookings.vaccination ";
    $query .= "AND bookings.location = locations.id ";
    $query .= "AND bookings.done != 1 "; //Checks if the appointment is already done
    $query .= "ORDER BY bookings.date";
    

    $preparedquery = $dbaselink->prepare($query);
    $preparedquery->bind_param("i", $id);
    $preparedquery->execute();

    if ($preparedquery->errno) {
        // echo "Fout bij uitvoeren commando";
    } else {
        $result = $preparedquery->get_result();

        if ($result->num_rows === 0) {
            // echo "geen gegevens";
        } else {

            $textForHtml = "";

            while ($row = $result->fetch_assoc()) {
                $textForHtml .= '<div class="card"><div class="card-body"><p class="card-text">';
                $textForHtml .= "<strong>Locatie / Vaccin</strong><br>";
                $textForHtml .= $row['adres'] . " " . $row['houseNumber'] . "<br>";
                $textForHtml .= $row['city'] . ", " . $row['zipcode'] . "<br>";
                $textForHtml .= $row['productname'] . " | " . $row['manufacturer'];
                $textForHtml .= '</p><hr><p class="card-text">';
                $textForHtml .= "<strong>Datum / Tijd</strong><br>";
                $textForHtml .= date("jS F, Y", strtotime($row['date'])) . "<br>";
                $textForHtml .= $row['timeslot'];
                $textForHtml .= "</p>";
                $textForHtml .= '<a href="/agendapage/deleteresult.php?id=' . $row['id'] . '" class="btn btn-rivm">Afspraak annuleren</a>';
                $textForHtml .= '</div></div>';
            }
        }
    }
    $preparedquery->close();

    include("database/closedb.php");
}
?>

<!DOCTYPE html>

<html>

    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Welkompagina";
            include("include/view/user/head.php");
        ?>
        <script src="/js/main.js"></script>
    </head>

    <body>

        <!-- This is our navbar -->
        <?php include("include/view/user/navbar.php"); ?>

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

                <div class="row">

                    <div class="col-6 col-md-6">
                        <?php
                            if( isset($textForHtml) ){
                                echo '<h5>Uw vaccinatie afspraak(en)</h5><a href="/pdf/">Print reservering(en)</a><hr>
                                <div class="card-deck">
                                ' . $textForHtml . '</div>';
                            } else {
                                echo '<img src="./img/RIVM-logo.png" class="d-none d-sm-block" style="max-width: 75%" alt="">
                                <img src="./img/RIVM-logo.png" class="d-block d-sm-none" style="max-width: 100%" alt="">';
                            }
                        ?>
                    </div>

                    <?php $result = isset($_SESSION['userid']) ? ["Reserveren", 'agendapage/index.php'] : ["Registreren", 'registratiepage/registreer.php']; ?>


                    <a class="col-6 col-md-6 register-link link--arrowed" href="<?php echo $result[1]; ?>">

                        <div class="row p-2">

                            <div class="col-10">

                                <h2 class="d-none d-sm-block"><?php echo $result[0]; ?></h2>

                                <h5 class="d-block d-sm-none"><?php echo $result[0]; ?></h5>

                            </div>

                            <div class="col-2">

                                <svg class="arrow-icon d-none d-sm-block" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 32 32">

                                    <g fill="none" stroke="#fdf6bb" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">

                                        <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>

                                        <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>

                                    </g>

                                </svg>

                            </div>

                            <div class="col-10">

                                <?php $result = isset($_SESSION['userid']) ? "Maak een afspraak voor uw vaccinatie." : "Maak een account aan."; ?>

                                <p><?php echo $result; ?></p>

                            </div>

                        </div>

                    </a>

                </div>

            </div>

        </section>



        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">

            <div class="container text-center">

                <div id="carouselSkills" class="carousel skills slide bg-skills rounded mb-4" data-ride="carousel">

                    <div class="carousel-inner" role="listbox">

                    </div>

                    <a class="carousel-control-prev" href="#carouselSkills" role="button" data-slide="prev">

                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>

                        <span class="sr-only">Previous</span>

                    </a>

                    <a class="carousel-control-next" href="#carouselSkills" role="button" data-slide="next">

                        <span class="carousel-control-next-icon" aria-hidden="true"></span>

                        <span class="sr-only">Next</span>

                    </a>

                </div>



                <div class="row">

                    <div class="col-sm-4 mb-2">

                        <div class="card">

                            <div class="card-body">

                                <h5 class="card-title">Corona</h5>

                                <p class="card-text">Veiligheid van het coronavaccin staat bovenaan. Hier gelden strenge...</p>

                                <button type="button" class="btn btn-rivm" data-toggle="modal" data-target="#coronaModal">Verder lezen</button>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-4 mb-2">

                        <div class="card">

                            <div class="card-body">

                                <h5 class="card-title">RIVM</h5>

                                <p class="card-text">We bewaken een veilige leefomgeving met milieumetingen en bestrijdi...</p>

                                <button type="button" class="btn btn-rivm" data-toggle="modal" data-target="#rivmModal">Verder lezen</button>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-4 mb-2">

                       <div class="card">

                            <div class="card-body">

                                <h5 class="card-title">GGD</h5>

                                <p class="card-text">Alleen al bij de GGD’en zijn er 12.000 professionals – artsen, verp...</p>

                                <button type="button" class="btn btn-rivm" data-toggle="modal" data-target="#ggdModal">Verder lezen</button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">

            <div class="container">

                    <div class="row">

                        <div class="col-6">

                            <h4>Bezoekadres</h4>

                            <hr align="left" width="50%">

                            <h5 class="pt-2">Rijksinstituut voor Volksgezondheid en Milieu</h5>

                            <p>Antonie van Leeuwenhoeklaan 9<br>3721 MA Bilthoven</p>

                            <h5 class="pt-2">Contactinformatie</h5>

                            <a href="tel:+030-2749111">030-274 91 11</a>

                            <h5 class="pt-2">Email</h5>

                            <a href="mailto:info@rivm.nl">info@rivm.nl</a><br>

                            <br>

                            <h4>Briefadres</h4>

                            <hr align="left" width="50%">

                            <h5 class="pt-2">RIVM</h5>

                            <p>Postbus 1<br>3720 BA Bilthoven</p>

                        </div>

                        <div class="col-6">

                            <div>

                                <p>U kunt het RIVM bereiken met het openbaar vervoer of met de auto</p>

                                <hr>

                                <iframe src="https://maps.google.com/maps?width=300&amp;height=300&amp;hl=en&amp;q=Antonie%20van%20Leeuwenhoeklaan%209%2C%203721%20MA%20Bilthoven+(Titel)&amp;ie=UTF8&amp;t=&amp;z=15&amp;iwloc=B&amp;output=embed" width="100%" height="320" frameborder="0" style="border:0"></iframe>

                            </div>

                            <br />

                        </div>

                    </div>

            </div>

        </section>



        <section class="p-3 mt-3" style="background-color: #fdf6bb;">

            <div class="container text-center">

                <p>© ALLE RECHTEN VOORBEHOUDEN</p>

            </div>

        </section>



        <div class="modal fade" id="coronaModal" tabindex="-1" role="dialog" aria-labelledby="coronaModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="coronaModalLabel">Corona</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <div class="modal-body p-5">

                        <h5>Veiligheid coronavaccin</h5>

                        <p>Veiligheid van het coronavaccin staat bovenaan. Hier gelden strenge eisen voor. Net als bij andere vaccins. Bij twijfel over de veiligheid van een vaccin mag het niet worden toegelaten in Nederland. Ook voor de bijwerkingen gelden strenge regels. De voordelen (de werkzaamheid van vaccins) moeten groter zijn dan de nadelen (eventuele bijwerkingen).</p>

                        <h5>Goedkeuring coronavaccins</h5>

                        <p>De vaccins moeten veilig en betrouwbaar zijn en goed werken. Daar oordeelt het Europees Geneesmiddelenbureau (EMA) samen met het College ter Beoordeling van Geneesmiddelen (CBG) over. Ook nadat vaccins zijn goedgekeurd, worden ze in de gaten gehouden. Er komt een Europees bewakingssysteem om de veiligheid van vaccins in de gaten te houden.</p>

                        <h5>Snelle ontwikkeling coronavaccins</h5>

                        <p>Coronavaccins zijn snel ontwikkeld door verschillende fabrikanten. Dit heeft een paar redenen: Wereldwijd werken meerdere bedrijven aan verschillende coronavaccins. Ook wordt er veel kennis gedeeld. Veel onderzoeken vinden op hetzelfde moment plaats. En niet na elkaar. Dat scheelt tijd. Onafhankelijke medicijnautoriteiten zetten extra mensen in. Ook beoordelen zij de resultaten tussendoor. Wilt u meer weten over de snelle ontwikkeling en veiligheid van coronavaccins? Lees het interview met klinisch onderzoeker, Leonoor Wijnans, die verder ingaat op deze vraag.</p>

                        <h5>Bijwerkingen</h5>

                        <p>De coronavaccins zijn getest op tienduizenden mensen. Sommige mensen hebben een paar dagen last van spierpijn of koorts. Dat gebeurt vaker bij vaccinaties. De meeste bijwerkingen beginnen binnen 6 weken na vaccinatie. Deze gaan bijna altijd vanzelf over. Het kan ook zijn dat er onbekende bijwerkingen optreden bij het coronavaccin. Die kans is klein. Neem bij onbekende bijwerkingen of als u zich zorgen maakt contact op met uw huisarts.</p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Afsluiten</button>

                    </div>

                </div>

            </div>

        </div>



        <div class="modal fade" id="rivmModal" tabindex="-1" role="dialog" aria-labelledby="rivmModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="rivmModalLabel">RIVM</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <div class="modal-body p-5">

                        <p>Het Rijksinstituut voor Volksgezondheid en Milieu (RIVM Rijksinstituut voor Volksgezondheid en Milieu ) zet zich al meer dan 100 jaar in voor een gezonde bevolking in een gezonde leefomgeving. Met een centrale rol in de infectieziektebestrijding en in landelijke preventie- en screeningsprogramma's. Via onafhankelijk (wetenschappelijk) onderzoek op het vlak van Volksgezondheid, Zorg, Milieu en Veiligheid. Als 'trusted advisor' voor de samenleving. Zo ondersteunt het RIVM burgers, professionals en overheden bij de uitdaging onszelf en onze leefomgeving gezond te houden.</p>

                        <p>Het Rijksinstituut voor Volksgezondheid en Milieu (RIVM Rijksinstituut voor Volksgezondheid en Milieu ) zorgt voor een goede geboortescreening, het bestrijden van infectieziekten en heeft een centrale rol bij bevolkingsonderzoeken. Ook onderzoeken we wat er nodig is voor goede zorg, veilige producten en een gezonde leefomgeving. Dit doen we via onafhankelijk (wetenschappelijk) onderzoek op het vlak van Volksgezondheid en Zorg, en Milieu en Veiligheid. Als 'trusted advisor' voor de samenleving.</p>

                        <p>We bewaken een veilige leefomgeving met milieumetingen en bestrijding van incidenten. Dit doen we in opdracht van de overheid en vaak samen met andere onderzoeks- en kennisinstituten, nationaal en internationaal. Ook hebben we een RIVM Rijksinstituut voor Volksgezondheid en Milieu -programma voor eigen onderzoek, innovatie en kennisontwikkeling. Met dit Strategisch Programma RIVM (SPR) richten we ons op onderwerpen die in de toekomst invloed kunnen hebben op onze volksgezondheid en leefomgeving.</p>

                        <p>Elk jaar brengt het RIVM talloze rapporten en adviezen uit over volksgezondheid en gezondheidszorg, voeding, natuur en milieu en rampenbestrijding.</p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Afsluiten</button>

                    </div>

                </div>

            </div>

        </div>



        <div class="modal fade" id="ggdModal" tabindex="-1" role="dialog" aria-labelledby="ggdModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="ggdModalLabel">GGD</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <div class="modal-body p-5">

                        <p>De publieke gezondheid: de gezondheid van ons allemaal.</p>

                        <p>De publieke gezondheidszorg zet zich in voor de gezondheid van alle inwoners van Nederland. Voor hen die zelf kunnen kiezen voor een gezonde levensstijl, maar bovenal voor de mensen die extra hulp nodig hebben om gezonder te leven.</p>

                        <p>In de publieke gezondheidszorg draait het om de vraag: hoe we de hele Nederlandse bevolking kunnen beschermen tegen risico’s waarop mensen zelf onvoldoende of geen invloed hebben.</p>

                        <p>Alleen al bij de GGD’en zijn er 12.000 professionals – artsen, verpleegkundigen, onderzoekers en beleidsmakers – die zich hier dagelijks voor inzetten. Hoe? Via bijvoorbeeld vaccinatieprogramma’s, bevolkingsonderzoeken naar kanker, gezondheidscampagnes en ondersteuning bij stoppen met roken. Of door de luchtkwaliteit te monitoren en gezondheidsschade door luchtverontreiniging aan te pakken. En niet te vergeten de vaak onzichtbare inzet gericht op veiligheid. Om rampen en calamiteiten te voorkomen, of het zoveel mogelijk beperken van hun impact.</p>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Afsluiten</button>

                    </div>

                </div>

            </div>

        </div>

    </body>

</html>