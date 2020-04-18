<?php
  //Inizializzo una sessione.
  session_start();

  //Eseguo il require dei file esterni necessari.
  require_once('db_connection.php');

  //Creao gli oggetti delle classi necessarie;
  $conn = new connection();

  //Creo gli attributi necessari.
  $result = null;

  //Controllo se é stato eseguito il POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $conn->getAmministratorCredential($_POST["inputUsername"], $_POST["inputPassword"]);
    //Se il checkbox "remember me" é selezionato salvo l'username e la password
    //dell'amministratore in variabili di sessione.
    if (isset($_POST["inputRememberMe"]) && $_POST["inputRememberMe"] == true) {
      $_SESSION["inputUsername"] = $_POST["inputUsername"];
      $_SESSION["inputPassword"] = $_POST["inputPassword"];
    }else {
      $_SESSION["inputUsername"] = null;
      $_SESSION["inputPassword"] = null;
    }
    if ($_SESSION["inputUsername"] != null && $_SESSION["inputPassword"] != null) {
      $result = $conn->getAmministratorCredential($_SESSION["inputUsername"], $_SESSION["inputPassword"]);
    }else {
      $result = $conn->getAmministratorCredential($_POST["inputUsername"], $_POST["inputPassword"]);
    }
    //Controllo se il valore di ritorno della funzione che controlla lo stato del flag e le credenziali
    //dell'amministrtore sono corretti.
    //Account non abilitato(return 0); credenziali non corrette(return 1); tutto corretto(return 2).
    //Se le credenziali ed il flag sono corretti vengo reindirizzato alle pagine amministrative,
    //altrimenti viene stampato un alert a schermo.
    if ($result == 0) {
      echo "<script type='text/JavaScript'>alert('Account bloccato.');</script>";
    }elseif ($result == 1) {
      echo "<script type='text/JavaScript'>alert('Username o password incorretti');</script>";
    }else {
      header("Location: ../../sitoAdmin/index.php");
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Template preso da: https://bootsnipp.com/snippets/a6Pdk -->

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Progetto SAMT Gestione traffico veicolare I4AC 2019">
    <meta name="author" content="Alessandro Gomes">
    <link rel="icon" href="../img/favicon.ico">

    <title>Gestione traffico veicolare</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/grayscale.css" rel="stylesheet">

    <!-- CSS for the login form -->
    <link href="../css/login.css" rel="stylesheet" id="bootstrap-css">

  </head>
  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="../index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="login.php">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <div class="card card-container">
        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
        <p id="profile-name" class="profile-name-card"></p>
        <form class="form-signin" action="" method="post">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
            <div id="remember" class="checkbox">
                <label>
                    <input type="checkbox" name="inputRememberMe" value="1"> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
        </form>
      </div>
    </div>
  </body>
</html>
