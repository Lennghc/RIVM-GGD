<?php

/*******************************************
* updateform.php
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

	if ( (empty($_GET['id'])) || !(isset($_GET['id'])) || !(is_numeric($_GET['id'])) ){
		$_SESSION['message'] = alertMsg('Error: Geen id meegegeven!', null, "danger");
		header("Location: index.php");
		exit;
	} else {
		$id = $_GET['id'];
	}

	//Select our user data from the database

	$query  = "SELECT id, firstname, lastname, email, ranking, birthdate, bsn ";
	$query .= "FROM users ";
	$query .= "WHERE id = ? ";
	$query .= "LIMIT 1 ";

	$preparedquery = $dbaselink->prepare($query);
	$preparedquery->bind_param("i", $id);
	$preparedquery->execute();

	if ($preparedquery->errno) {
		$_SESSION['message'] = alertMsg('Gebruiker kon niet worden bewerkt, probeer het later nog een keer.', null, "danger");
		$preparedquery->close();
		include("../../database/closedb.php");
		header("Location: index.php");
		exit;
	} else {
		$result = $preparedquery->get_result();

		if($result->num_rows === 0) {
			$_SESSION['message'] = alertMsg('Deze gebruiker bestaat niet. Probeer nogmaals.', null, "danger");
			$preparedquery->close();
			include("../../database/closedb.php");
			header("Location: index.php");
			exit;
		} else {
			$row = $result->fetch_assoc();
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$email = $row['email'];
			$ranking = $row['ranking'];
			$birthdate = $row['birthdate'];
			$bsn = $row['bsn'];
		}
	}
	$preparedquery->close();
	include("../../database/closedb.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Gebruiker wijzigen";
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
                <div class="col-sm-12 mb-10">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gebruiker wijzigen</h5>

                            <form method="post" action="updateresult.php">
                                <input type="hidden" name="id" value=<?php echo $id;?>><br>
								<div class="form-row">
									<div class="form-group col-md-6">
                                    	<label for="inputFirstname">Naam</label>
                                    	<input type="text" class="form-control" name="firstName" value="<?php echo $firstname; ?>"  required id="inputFirstname">
									</div>
									<div class="form-group col-md-6">
										<label for="inputLastname">Achternaam</label>
                                        <input type="text" class="form-control" name="lastName"value="<?php echo $lastname; ?>" required id="inputLastname">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputBSN">Burgerservicenummer (BSN)</label>
										<input type="number" class="form-control" name="bsn" value="<?php echo $bsn; ?>" required id="inputBSN">
									</div>
									<div class="form-group col-md-6">
										<label for="inputBirthdate">Geboortedatum</label>
										<input type="date" class="form-control"	name="birthDate" value="<?php echo $birthdate; ?>" required id="inputBirthdate">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="email">E-mailadres</label>
										<input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required id="email">
									</div>
									<div class="form-group col-md-3">
										<label for="NewPassword">Wachtwoord</label>
										<input type="text" class="form-control" name="password" id="inputNewPassword">
									</div>
									<div class="form-group col-md-3">
										<label for="inputRanking">Rangschikking</label>
										<select class="form-control" name="ranking" id="inputRanking">
											<?php include('../../include/sqlrankinguser.php'); ?>
										</select>
									</div>
								</div>

                                <input type="submit" class="btn btn-rivm" data-toggle="modal" data-target="#coronaModal" value="Gebruiker wijzigen">
                                <a href="deleteconfirm.php?id=<?php echo $id;?>" class="btn btn-danger float-right ml-1">Verwijder</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-3 mt-3" style="position: absolute; bottom: 0; width: 100%; heigth: 60px; background-color: #fdf6bb;">
            <div class="container text-center">
                <p>© ALLE RECHTEN VOORBEHOUDEN</p>
            </div>
        </section>

		<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Wachtwoord wijzigen</h5>
					</button>
				</div>
				<div class="modal-body">
					<p>Weet je zeker dat je het wachtwoord wilt gaan wijzigen? Voer dan het nieuwe wachtwoord in voor de gebruiker</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rivm" id="createNewPass" data-dismiss="modal">Doorgaan</button>
					<button type="button" class="btn btn-danger" id="ignoreNewPass" data-dismiss="modal">Afbreken</button>
				</div>
				</div>
			</div>
		</div>

		<script>
			$(function() {
				$('#inputNewPassword').on('click', function() {
					$('#inputNewPassword').focusout();
					$('#passwordModal').modal('toggle');
				});

				$('#createNewPass').on('click', function() {
					$('#inputNewPassword').unbind('click');
				});

				$('#ignoreNewPass').on('click', function() {
					$('#inputNewPassword').val("");
				});

			});
		</script>
</body>
</html>