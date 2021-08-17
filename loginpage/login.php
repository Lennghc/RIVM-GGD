<?php
	if(!isset($_SESSION)){
		session_start();
	}

	//Redirect user if already loggedin
	if( isset($_SESSION['userid']) ){
		header("Location: /index.php");
		exit;
	}
?>
<!DOCTYPE html>

<html>

	<head>
        <?php 
            //We define our header here. You can still add page to page specific stylesheets or scripts here
            //Use $title to adjust the title seen on top browser bar
            $pageTitle = "Login";
            include("../include/view/user/head.php");
        ?>
		<link href="../css/login.css" rel="stylesheet">
    </head>

<body>

	<!-- This is our navbar -->
	<?php include("../include/view/user/navbar.php"); ?>

	<!-- Main content -->

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

					<form method="post" action="loginconfirm.php">

						<div class="input-group mb-3">

							<div class="input-group-append">

								<span class="input-group-text"><i class="backgroundblack fas fa-user"></i></span>

							</div>

                            <input class="form-control input_user" type="text" name="email" placeholder="Uw e-mailadres" required>

						</div>

						<div class="input-group mb-2">

							<div class="input-group-append">

								<span class="input-group-text"><i class="backgroundblack fas fa-key"></i></span>

							</div>

                            <input class="form-control input_pass" type="password" name="password" placeholder="Uw wachtwoord" required>

						</div>

							<div class="d-flex justify-content-center mt-3 login_container">

                                <input class="backgroundblack btn login_btn" type="submit" value="Inloggen">

				            </div>

						</div>

					</form>

					<div class="mt-4">

						<div class="d-flex justify-content-center links">

							<a href="../registratiepage/registreer.php" id="password">Maak een account</a>

						</div>

					</div>

				</div>

			</div>

		</div>

</body>

</html>

