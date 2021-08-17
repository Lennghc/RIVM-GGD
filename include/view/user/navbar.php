<?php
    if(!isset($_SESSION)){
        session_start();
    }
?>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark" style="background-color: #f9e11e;">

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>

</button>

    <div class="collapse navbar-collapse" id="navbarNav">

    <ul class="navbar-nav mx-auto">

        <li class="nav-item">

            <a class="nav-link" href="../index.php">Home</a>

        </li>

        <li class="nav-item">

            <a class="nav-link" href="/agendapage/index.php">Reserveren</a>

        </li>

        <a class="d-none d-lg-block" href="../index.php">

        <img src="/img/rivm_logo_head.svg" style="width: 25px;">

        </a>

        <li class="nav-item">

            <a class="nav-link" href="/contactpage/index.php">Contact</a>

        </li>

        <li class="nav-item">

            <?php 

                //This is a ternary oparetor, first we look if its defined and if we are admin. Then we display it.

                //works with a array so we can push multiple args in one var (0 = Title, 1 = Url name)

                //Last we have another ternary oparetor inside itself to see if we logged in or not

                $result = isset($_SESSION['userid']) ? ['Uitloggen', '/functions/logout.php'] : ['Inloggen', '/loginpage/login.php'];

            ?>

            <a class="nav-link" href="<?php echo $result[1]; ?>"><?php echo $result[0]; ?></a>

        </li>

        </ul>

    </div>

</nav>