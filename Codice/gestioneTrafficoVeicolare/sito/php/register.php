<?php
  $result = null;
  //Eseguo il require dei file esterni necessari.
  require_once('db_connection.php');

  //Creao gli oggetti delle classi necessarie;
  $conn = new connection();

  //Controllo se é stato eseguito il POST e richiamo la funzione desiderata.
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $conn->saveAmministratorCredential($_POST["inputUsername"], $_POST["inputPassword"]);
  }
  if ($result) {
    echo "<script type='text/JavaScript'>alert('Username già presente');</script>";
  }else {
    echo "<script type='text/JavaScript'>
    alert('Nuovo utente amministratore registrato, aspettare che amministratore del sistema lo abiliti');
    </script>";
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

    <!-- Script e css for the login form -->
    <link href="../css/login.css" rel="stylesheet" id="bootstrap-css">

  </head>
  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
        data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
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
        <form class="form-register" action="" method="post">
          <!-- Username -->
          <label class="control-label"  for="username">Username</label>
          <div class="controls">
            <input type="text" id="inputUsername" name="inputUsername" placeholder="" class="input-xlarge" required>
          </div>
          <!-- Password-->
          <label class="control-label" for="password">Password</label>
          <div class="controls">
            <input type="password" id="inputPassword" name="inputPassword" placeholder="" class="input-xlarge" required>
          </div>
          <br>
          <!-- Submit-->
          <div class="controls">
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Register</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
