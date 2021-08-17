<!DOCTYPE html>
<html>
    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Contactpagina";
            include("../include/view/user/head.php");
        ?>
    </head>
    <body>

        <!-- This is our navbar -->
        <?php include("../include/view/user/navbar.php"); ?>
        
        <section class="mb-5 p-5 mt-3" style="background-color: #fdf6bb;">
            <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <p>Op deze pagina vindt u de contactgegevens van en de routebeschrijving naar het RIVM Rijksinstituut voor Volksgezondheid en Milieu. Ook leest u hoe u een vraag kunt stellen aan het RIVM, hoe onze klachtenregeling werkt en wat de inkoopvoorwaarden zijn.<br><br>

                            Vragen over het coronavirus of de vaccinatie tegen corona? Neem dan contact op via 0800-1351, dit is het publieksinformatienummer van de Rijksoverheid. Dit telefoonnummer is dagelijks bereikbaar van 8:00 tot 20:00 uur.<br><br>

                            Bent u journalist? Ga dan naar persinformatie. (Hierop gegevens: Telefoon 030-274 91 66 en E-mail persinfo@rivm.nl)</p>

                            <h4>Bezoekadres</h4>
                            <hr align="left" width="50%">
                            <h5 class="pt-2">Rijksinstituut voor Volksgezondheid en Milieu</h5>
                            <p>Antonie van Leeuwenhoeklaan 9<br>3721 MA Bilthoven</p>
                            <h5 class="pt-2">Contactinformatie</h5>
                            <a href="tel:+030-2749111">030-274 91 11</a>
                            <h5 class="pt-2">Email</h5>
                            <a href="mailto:info@rivm.nl">info@rivm.nl</a><br>
                            <br>
                            <h4>Correspondentieadres</h4>
                            <hr align="left" width="50%">
                            <h5 class="pt-2">RIVM</h5>
                            <p>Postbus 1<br>3720 BA Bilthoven</p>
                        </div>
                        <div class="col-6">
                            <div>
                                <p>U kunt het RIVM bereiken met het openbaar vervoer of met de auto</p>
                                <p>BTW Belasting Toegevoegde Waarde -identificatienummer - of <br>VAT-nummer NL 8217.72.302.B01</p>
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
        
    </body>
</html>
<?php
?>