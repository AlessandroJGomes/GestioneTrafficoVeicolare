<?php
  //Inizializzo una sessione.
  session_start();

  //Eseguo il require dei file esterni necessari.
  require_once('db_connection.php');

  //Creo gli attributi necessari.
  $check = null;
  $tot = null;
  //Prezzo in franchi.
  $keyValue = 200;
  $flag = null;

  //Creao gli oggetti delle classi necessarie;
  $conn = new connection();

  //Controllo se é stato eseguito il POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['inputNumberKeys'] = $_POST['inputNumberKeys'];
    $flag = $_SESSION['inputNumberKeys'];
    //Calcolo il prezzo totale
    $tot = $_SESSION['inputNumberKeys'] * $keyValue;
    for ($i=1; $i <= $_SESSION['inputNumberKeys']; $i++) {
      if ($_POST["radioButtonSociety"] == "1") {
        $check = $conn->saveNewUser(1,
        $_POST["inputSocietyName"], $_POST["inputNamePersonInCharge"],
        $_POST["inputSurnamePersonInCharge"], $_POST["inputTelephoneNumberPersonInCharge"],
        $_POST["inputSocietyAddress"], $_POST["inputSocietyAddressNumber"],
        $_POST["inputSocietyPostalNumber"], $_POST["inputSocietyCity"],
        $_POST["inputNameHolder_".$i], $_POST["inputSurnameHolder_".$i],
        $_POST["inputHolderAddress_".$i], $_POST["inputHolderAddressNumber_".$i],
        $_POST["inputHolderPostalNumber_".$i], $_POST["inputHolderCity_".$i], $_POST["inputCarBrand_".$i],
        $_POST["inputCarColor_".$i], $_POST["inputCarModel_".$i],
        $_POST["inputCarType_".$i], $_POST["inputPlateNumber_".$i], $_POST["inputEmailHolder_".$i],
        $_SESSION['inputNumberKeys'], $tot, $flag);
        $flag -= 1;
      }else {
        $conn->saveNewUser(0, "", "", "", "", "", "", "", "",
        $_POST["inputNameHolder_".$i], $_POST["inputSurnameHolder_".$i],
        $_POST["inputHolderAddress_".$i], $_POST["inputHolderAddressNumber_".$i],
        $_POST["inputHolderPostalNumber_".$i], $_POST["inputHolderCity_".$i], $_POST["inputCarBrand_".$i],
        $_POST["inputCarColor_".$i], $_POST["inputCarModel_".$i],
        $_POST["inputCarType_".$i], $_POST["inputPlateNumber_".$i], $_POST["inputEmailHolder_".$i],
        $_SESSION['inputNumberKeys'], $tot, $flag);
        $flag -= 1;
      }
    }
    //Controllo il valore di ritorno della funzione di salvataggio delle nuove richieste.
    if ($check == 1) {
      echo "<script type='text/JavaScript'>
      alert('Valori già presente nel database, nuovo campo aggiunto nelle altre tabelle.');
      </script>";
    }else {
      echo "
      <script type='text/JavaScript'>
        var keyCost = 200;
        var total = ". $_SESSION["inputNumberKeys"] ." * keyCost;
        alert('Hai ordinato ' + ". $_SESSION["inputNumberKeys"] ." + ' chiave/i, il costo totale é di '
        + total + 'CHF. Pagare il seguente importo al conto XXX-XXX-XXX-XXX');
      </script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Template preso da: https://startbootstrap.com/template-overviews/grayscale/, ma modificato. -->

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

    <!-- Script for the formulary form -->
    <script src="../js/formulary.js"></script>

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
    <header class="masthead">
      <div class="container d-flex h-50 align-items-center">
        <div class="mx-auto text-center">
          <h1 class="mx-auto my-0 text-uppercase">Benvenuto</h1>
          <h2 class="text-white-50 mx-auto mt-2 mb-5">Compilare i campi sottostanti</h2>
        </div>
      </div>
      <main role="main">
        <div class="jumbotron">
          <form action="" method="post" id="form">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="società">Possiedi o fai parte di una società?</label>
                <br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="radioButtonSociety" id="radioYes" value="1" onclick="hide(this)" checked>
                  <label class="form-check-label" for="società">Si</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="radioButtonSociety" id="radioNo" value="0" onclick="hide(this)">
                  <label class="form-check-label" for="società" >No</label>
                </div>
              </div>
            </div>
            <fieldset id="society">
              <legend>Dati società</legend>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="societyName">Nome società*</label>
                  <input type="text" class="form-control notRequired" name="inputSocietyName" placeholder="Nome società" required value="nome societa">
                </div>
                <div class="form-group col-md-3">
                  <label for="namePersonInCharge">Nome responsabile*</label>
                  <input type="text" class="form-control notRequired" name="inputNamePersonInCharge" placeholder="Nome responsabile" required value="nome responsabile">
                </div>
                <div class="form-group col-md-3">
                  <label for="surnamePersonInCharge">Cognome responsabile*</label>
                  <input type="text" class="form-control notRequired" name="inputSurnamePersonInCharge" placeholder="Cognome responsabile" required value="cognome responsabile">
                </div>
                <div class="form-group col-md-3">
                  <label for="telephoneNumberPersonInCharge">Telefono responsabile*</label>
                  <input type="text" onkeyup="control(this, telephoneNumberRegex)" class="form-control notRequired" name="inputTelephoneNumberPersonInCharge" id="inputTelephoneNumberPersonInCharge" placeholder="Telefono responsabile" required value="0919881232">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="societyAddress">Via*</label>
                  <input type="text" class="form-control notRequired" name="inputSocietyAddress" placeholder="Via" required value="via societa">
                </div>
                <div class="form-group col-md-3">
                  <label for="societyAddressNumber">N° Civico*</label>
                  <input type="text" class="form-control notRequired" name="inputSocietyAddressNumber" placeholder="N° Civico" required value="ncivico societa">
                </div>
                <div class="form-group col-md-3">
                  <label for="societyPostalCode">CAP*</label>
                  <input type="text" onkeyup="control(this, capRegex)" class="form-control notRequired" name="inputSocietyPostalNumber" id="inputSocietyPostalNumber" placeholder="CAP" required value="6900">
                </div>
                <div class="form-group col-md-3">
                  <label for="societyCity" >Città*</label>
                  <input type="text" class="form-control notRequired" name="inputSocietyCity" placeholder="Città"required  value="citta societa">
                </div>
              </div>
            </fieldset>
            <div class="nKeys">
              <div class="form-group col-md-auto">
                <label for="nameHolder">Numero di chiavi</label>
                <input type="number" class="form-control nKeys" id="inputNumberKeys" name="inputNumberKeys" value="1" data-numKeys="1" readonly>
              </div>
            </div>
            <div id="detentori">
              <fieldset id="detentore_1">
                <legend>Dati detentore</legend>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label for="nameHolder">Nome*</label>
                    <input type="text" class="form-control" name="inputNameHolder_1" placeholder="Nome detentore" required value="nome">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="surnameHolder">Cognome*</label>
                    <input type="text" class="form-control" name="inputSurnameHolder_1" placeholder="Cognome detentore" required value="cognome">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="holderAddress">Via*</label>
                    <input type="text" class="form-control" name="inputHolderAddress_1" placeholder="Via" required value="via">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="holderAddressNumber">N° Civico*</label>
                    <input type="text" class="form-control" name="inputHolderAddressNumber_1" placeholder="N° Civico" required value="ncivico">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="holderPostalCode">CAP*</label>
                    <input type="text" onkeyup="control(this, capRegex)" class="form-control" name="inputHolderPostalNumber_1" id="inputHolderPostalNumber_1" placeholder="CAP" required value="6900">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="holderCity">Città*</label>
                    <input type="text" class="form-control" name="inputHolderCity_1" placeholder="Città" required value="citta">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label for="emailHolder">Email*</label>
                    <input type="email" class="form-control" name="inputEmailHolder_1" placeholder="Email detentore" required value="a.a@a.a">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="carBrand">Marca veicolo*</label>
                    <input type="text" class="form-control" name="inputCarBrand_1" placeholder="Marca veicolo" required value="marca">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="carColor">Colore veicolo*</label>
                    <input type="text" class="form-control" name="inputCarColor_1" placeholder="Colore veicolo" required value="colore">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="carModel">Modello veicolo*</label>
                    <input type="text" class="form-control" name="inputCarModel_1" placeholder="Modello veicolo" required value="modello">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="carType">Tipo veicolo*</label>
                    <input type="text" class="form-control" name="inputCarType_1" placeholder="Modello veicolo" required value="tipo">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="plateNumber">N° Targa*</label>
                    <input type="text" onkeyup="control(this, plateRegex)" class="form-control" name="inputPlateNumber_1" placeholder="N° Targa" required value="ntarga">
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="form-row">
              <div class="form-group col-md-auto">
                <label for="addKeys">Aggiungi chiavi</label>
                <br>
                <button type="button" class="btn btn-warning btn-circle" onclick="incrementKeys()"><b class="add">+</b></button>
              </div>
            </div>
            <div class="nKeys">
              <div class="form-group col-md-auto">
                <button type="submit" name="orderButton" id="orderButton" class="btn btn-secondary nKeys">Ordina</button>
              </div>
            </div>
          </form>
        </div>
      </main>
    </header>
    </div>
  </body>
</html>
