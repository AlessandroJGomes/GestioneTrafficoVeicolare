<?php
  $statistiche = null;
  $statistichePerSocietà = null;
  //Eseguo il require dei file esterni necessari.
  require_once('db_connection.php');
  //Creao gli oggetti delle classi necessarie;
  $conn = new connection();

  //Controllo se é stato eseguito il POST
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST["import"])) {
      $fileName = $_FILES["customFile"]["tmp_name"];
      if ($_FILES["customFile"]["size"] > 0) {
        $conn->csvImport($fileName);
      }else {
        echo "CSV vuoto!!!!!!!!!!";
      }
    }
  }
  $statistiche = $conn->getStatistics();
  $statistichePerSocietà = $conn->getSocietyStatistics();
  //print_r($statistiche);
  //$conteggioPerPassaggio = $conn->getNumberOfPassagesForCar();
?>

<!doctype html>
<html>
  <head>

    <!-- Template preso da: https://designrevision.com/downloads/shards-dashboard-lite/, ma modificato. -->

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Progetto SAMT Gestione traffico veicolare I4AC 2019">
    <meta name="author" content="Alessandro Gomes">
    <link rel="icon" href="../../sito/img/favicon.ico">

    <title>Gestione traffico veicolare</title>

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom css for this template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/shards-dashboards.1.1.0.min.css" id="main-stylesheet" data-version="1.1.0">
    <link rel="stylesheet" href="../css/extras.1.1.0.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">


    <!-- Custom styles for this template -->
    <link href="../css/admin.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
              <div class="d-table m-auto">
                <span class="d-none d-md-inline ml-1">Admin Dashboard</span>
              </div>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>
          <div class="nav-wrapper">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="../index.php">
                  <i class="material-icons">euro_symbol</i>
                  <span>Pagamenti</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="keys.php">
                  <i class="material-icons">vpn_key</i>
                  <span>Chiavi</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="statistics.php">
                  <i class="material-icons">equalizer</i>
                  <span>Statistiche</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="history.php">
                  <i class="material-icons">person</i>
                  <span>Lista utenti</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="registrationConfirm.php">
                  <i class="material-icons">accessibility</i>
                  <span>Lista registrazioni</span>
                </a>
              </li>
            </ul>
          </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
              <!-- Form per la barra di ricerca -->
              <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">
                <div class="input-group input-group-seamless ml-3">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                  <input class="navbar-search form-control" type="text" placeholder="Cerca...">
                </div>
              </form>
              <ul class="navbar-nav border-left flex-row ">
                <!-- Menu del Logout -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" role="button" href="#">
                    <img class="user-avatar rounded-circle mr-2" src="../img/admin.png" alt="User Avatar">
                    <span class="d-none d-md-inline-block">Admin</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item text-danger" href="../../sito/php/login.php">
                      <i class="material-icons text-danger">&#xE879;</i> Logout </a>
                  </div>
                </li>
              </ul>
            </nav>
          </div>
          <!-- End main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                <h3 class="page-title">Statistiche del traffico passante per la barriera</h3>
              </div>
            </div>
            <!-- End page header -->
            <!-- Begin Page Content -->
            <div class="container-fluid">
              <div class="card shadow mb-4">
                <div class="card-body">
                  <!-- Begin import CSV file -->
                  <form action="" method="post" name="uploadCSV" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-9 mb-3">
                        <input type="file" class="custom-file-input" id="customFile" name="customFile" accept=".csv">
                        <label class="custom-file-label" for="customFile">Importa il file CSV</label>
                      </div>
                      <div class="col-md-3 mb-3 container">
                        <button type="submit" id="submit" name="import" class="btn btn-primary">Import</button>
                      </div>
                    </div>
                  </form>
                  <!-- End import CSV file -->
                  <!-- Begin temporal filter -->
                  <form action="" method="post">
                    <div class="form-row">
                      <div class="form-group col-md-6 mb-3">
                        <label for="fromDate">Da</label>
                        <input type="date" class="form-control notRequired" name="inputFromDate">
                      </div>
                      <div class="form-group col-md-6 mb-3">
                        <label for="toDate">A</label>
                        <input type="date" class="form-control notRequired" name="inputToDate">
                      </div>
                    </div>
                  </form>
                  <!-- End temporal filter -->
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>N° targa</th>
                              <th>N° passaggi per auto</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php for ($i=0; $i < count($statistiche[0]); $i++): ?>
                            <tr>
                              <td><?php echo $statistiche[0][$i]['N_targa']; ?></td>
                              <td><?php echo $statistiche[1][$i]; ?></td>
                            </tr>
                            <?php endfor; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="container-fluid">
              <div class="card shadow mb-4">
                <div class="card-body">
                  <!-- Begin temporal filter -->
                  <form action="" method="post">
                    <div class="form-row">
                      <div class="form-group col-md-6 mb-3">
                        <label for="fromDate">Da</label>
                        <input type="date" class="form-control notRequired" name="inputFromDate">
                      </div>
                      <div class="form-group col-md-6 mb-3">
                        <label for="toDate">A</label>
                        <input type="date" class="form-control notRequired" name="inputToDate">
                      </div>
                    </div>
                  </form>
                  <!-- End temporal filter -->
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Nome società</th>
                              <th>N° passaggi per società (totale auto per società)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php for ($i=0; $i < count($statistichePerSocietà[0]); $i++): ?>
                            <tr>
                              <td><?php echo $statistichePerSocietà[0][$i]['Nome_societa']; ?></td>
                              <td><?php echo $statistichePerSocietà[1][$i]; ?></td>
                            </tr>
                            <?php endfor; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End main page -->
          </div>
          <footer class="main-footer d-flex p-2 px-3 bg-white border-top">
            <span class="copyright ml-auto my-auto mr-2">Copyright © 2018
              <a href="https://designrevision.com" rel="nofollow">DesignRevision</a>
            </span>
          </footer>
        </main>
      </div>
    </div>
  </body>
</html>
