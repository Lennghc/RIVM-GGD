<?php
	//  <!-- creator: Tygo Houweling
	//  date: 13-5-2021 -->

	include("../functions/alertMsg.php");

	//Make sure the session is running

	if(!isset($_SESSION)){
		session_start();
	}

	//Redirect user if already loggedin
	if( isset($_SESSION['userid']) ){
		header("Location: /index.php");
		exit;
	} else {
		if ( (empty($_GET['id'])) || !(isset($_GET['id'])) || !(is_numeric($_GET['id'])) ){
			header("Location: /index.php");
			exit;
		} else {
			$id = $_GET['id'];
		}
	}
?>
<!DOCTYPE html>
<html>
	 
 	<head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Registratie";
            include("../include/view/user/head.php");
        ?>
		<link href="../css/login.css" rel="stylesheet">
    </head>

	<body>

		<!-- This is our navbar -->
		<?php include("../include/view/user/navbar.php"); ?>

		<!-- This is our main content -->

		 <div class="container">
		 	<div class="d-flex justify-content-center mt-5">
				<?php

					if(!empty($_SESSION['message'])) {

						echo $_SESSION['message'];
						unset($_SESSION['message']);
					}

				?>
			</div>
			<div class="d-flex justify-content-center mt-5 p-5">
				<div class="user_card">
					<div class="d-flex justify-content-center">
						<div class="brand_logo_container">
							<img src="/img/rivm_logo_login.png" class="brand_logo" alt="Logo">
						</div>
					</div>
					<div class="d-flex justify-content-center form_container">

						<form method="post" action="registreerconfirm2.php">
							<?php echo '<input type="hidden" name="id" value="' . $_GET['id'] . '">' ?>

							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="backgroundblack fas fa-user"></i></span>
								</div>
                            	<input class="form-control input_user" type="text" name="firstname" placeholder="Voornaam" required>
							</div>

							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="backgroundblack fas fa-user"></i></span>
								</div>
                            	<input class="form-control input_pass" type="text" name="lastname" placeholder="Achternaam" required>
							</div>

							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="backgroundblack fas fa-id-card"></i></span>
								</div>
                            	<input class="form-control input_user" type="number" name="bsn" placeholder="bsn" required>
							</div>


							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="backgroundblack fas fa-calendar"></i></span>
								</div>
                            	<input class="form-control input_pass" type="date" name="birthdate" placeholder="Verjaardag(yyyy-mm-dd)" required>
							</div>

							<div class="d-flex justify-content-center mt-3 login_container">
                                <input class="backgroundblack btn login_btn" value="Registreer" type="submit">
				        	</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>