<?php

  /**
  * @author Alessandro Gomes
  * @version 05.02.2019
  * Questa classe gestisce la connessione al database e le iterazioni con esso tramite delle query contenute in funzioni specifiche.
  * Queste funzioni verranno poi utilizzate tramite richiamo dai file che necessitano tali funzioni.
  */
  class connection {

    //Connessione al db mysql da localhost.
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "gestione_traffico_veicolare";
    private $port = 3306;

    // Creo la variabile connessione
    public $conn;

    /**
    * Funzione che istanzia una nuova connessione mysqli.
    */
    function newConnection() {
      // Creo la connessione
      $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
      // Controllo la connessione
      if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
      }
    }

    /**
    * Questa funzione si occupa del salvataggio dei dati dell'amministratore
    * all'interno del database tramite una query con relativi controlli.
    * @param username L'username dell'amministratore.
    * @param password La password dell'amministratore.
    * @return boolean Ritorno true se il nuovo account esiste già, altrimenti false
    */
    function saveAmministratorCredential($username, $password) {
      $confirmed = 0;
      //Codifico la password ricevuta tramite l'algoritmo sha256.
      $passwordEncoded = hash("sha256", $password);
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permette il controllo dell'inserimento del nuovo username dell'amministratore,
      //se esiste già (true) o meno (false).
      $stmtUsernameControl = $this->conn->prepare("SELECT 1 FROM amministratore where Username = ?");
      $stmtUsernameControl->bind_param("s", $username);
      $stmtUsernameControl->execute();
      if ($stmtUsernameControl->fetch() != null) {
        return true;
      }else {
        //Query che permette l'inserimento delle credenziali dell'amministratore con il rispettivo flag
        //di conferma, quest'ultimo dovrà essere cambiato dall'amministartore del sito web,
        //utilizzando un prepared statement per evitare delle SQLInjection.
        $stmtNewAdministrator = $this->conn->prepare("INSERT INTO amministratore (Username, Password, Confermato) VALUES (?,?,?)");
        $stmtNewAdministrator->bind_param("ssi", $username, $passwordEncoded, $confirmed);
        //Eseguo la query.
        if(!$stmtNewAdministrator->execute()){
          echo "La query di inserimento delle credenziali dell'amministratore
          presente nella funzione setAmministratorCredential() non funziona (riga:57).";
        }
        return false;
      }
    }

    /**
    * Questa funzione si occupa dell'estrapolazione dei dati dell'amministratore dal database tramite una query,
    * innanzitutto controllo lo stato del suo flag (account abilitato o no) ed in seguito controllo le sue credenziali.
    * @param username L'username dell'amministratore.
    * @param password La password dell'amministratore.
    * @return int Ritorno una un valore interno a dipendenza del flag e delle credenziali:
    * account bloccato(0); credenziali errate(1); account sbloccato e credenziali corrette(2).
    */
    function getAmministratorCredential($username, $password) {
      $check = 1;
      //Codifico la password ricevuta tramite l'algoritmo sha256.
      $passwordEncoded = hash("sha256", $password);
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che controlla se le credenziali dell'amministratore che sta effettuando il Login
      //esistano o meno nella tabella amministratore.
      //Eseguo la query tramite un prepared statement per evitare delle SQLInjection.
      $stmtCredential = $this->conn->prepare ("SELECT 1 FROM amministratore where Username = ? AND Password = ?");
      $stmtCredential->bind_param("ss", $username, $passwordEncoded);
      //Eseguo la query.
      if($stmtCredential->execute()){
        //Controllo se le credenziali dell'amministratore sono corrette
        //Se non lo sono allora non può accedere alla parte amministrativa,
        //altrimenti controllo lo stato del flag.
        if ($stmtCredential->fetch() == 1) {
          //Query che permette l'estrapolazione dello stato del flag dell'amministratore,
          //utilizzando un prepared statement per evitare delle SQLInjection.
          $this->newConnection();
          $stmtFlag = $this->conn->prepare("SELECT 1 FROM amministratore where Username = ? AND Password = ? AND Confermato = ?");
          $stmtFlag->bind_param("ssi", $username, $passwordEncoded, $check);
          //Eseguo la query.
          if($stmtFlag->execute()){
            //Controllo se l'account é abilitato o meno
            //Se é anche abilitato (!= 0) allora eseguo il login, altrimenti l'account é bloccato
            if ($stmtFlag->fetch() != 0) {
              return 2;
            }else {
              return 0;
            }
          } else {
            echo "La query di selezione delle credenziali dell'amministratore
            presente nella funzione getAmministratorCredential() non funziona (riga:96).";
          }
        }else {
          return 1;
        }
      }else {
        echo "La query di selezione del flag dell'amministratore
        presente nella funzione getAmministratorCredential() non funziona (riga:85).";
      }
    }

    /**
    * Questa funzione riceve tutti i dati immessi nella pagina web del formulario
    * e si occupa di salvarli, tramite delle query, nelle rispettive tabelle con i rispettivi controlli.
    * @param society Valore "booleano" che avverte se sono stati inseriti i dati della società(1) oppure no(0).
    * @param societyName Stringa contenente il nome della società.
    * @param personInChargeName Stringa contenente il nome della persona in carica della società.
    * @param personInChargeSurname Stringa contenente il cognome della persona in carica della società.
    * @param personInChargeTelephone Stringa contenente il numero di telefono del responsabile della società.
    * @param societyAddress Stringa contenente la via della società.
    * @param societyAddressNumber Stringa contenente il numero civico della società.
    * @param societyPostalNumber Stringa contenente il CAP della società.
    * @param societyCity Stringa contenente la città della società.
    * @param holderName Stringa contenente il nome del detentore.
    * @param holderSurname Stringa contenente il cognome del detentore.
    * @param holderAddress Stringa contenente la via del detentore.
    * @param holderAddressNumber Stringa contenente il numero civico del detentore.
    * @param holderPostalNumber Stringa contenente il CAP del detentore.
    * @param holderCity Stringa contenente la città del detentore.
    * @param carBrand Stringa contenente la marca dell'auto.
    * @param carColor Stringa contenente il colore dell'auto.
    * @param carModel Stringa contenente il modello dell'auto.
    * @param carType Stringa contenente il tipo di auto.
    * @param plateNumber Stringa contenente il numero di targa.
    * @param emailHolder Stringa contenente l'email del detentore.
    * @param keysNumber Intero contenente il numero di chiavi del detentore.
    * @param totalCost Intero contenente il costo totale delle chiavi ordinate.
    * @param flag Intero contenente un flag che controlla quante volte eseguo l'inseriemnto nella tabella temporanea.
    * @return int Ritorno 1 se una delle chiavi primarie che sono state inserite esiste già.
    */
    function saveNewUser($society, $societyName, $personInChargeName,$personInChargeSurname,
    $personInChargeTelephone, $societyAddress, $societyAddressNumber, $societyPostalNumber,
    $societyCity, $holderName,$holderSurname, $holderAddress, $holderAddressNumber, $holderPostalNumber,
    $holderCity, $carBrand, $carColor, $carModel, $carType, $plateNumber, $emailHolder, $keysNumber, $totalCost, $flag) {
      //Creazione delle variabili importanti.
      $null = null;
      $value = null;
      $result = null;
      $temp = null;
      $returned = 0;
      $payment = 0;
      $ret = null;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Controllo se l'utente ha inserito i dati relativi alla società.
      if ($society == 1) {
        //Controllo se il nome della società é già presente nel databse.
        //Se non é presente eseguo le query d'inseriemnto nelle tre tabelle.
        if ($this->checkSocietyDuplicate($societyName) == null) {
          //Query utilizzata per l'inserimento dei dati relativi alla società all'interno della tabella corretta.
          //Uso un prepared statement per evitare le SQLInjection.
          $stmtSociety = $this->conn->prepare("INSERT INTO societa VALUES (?,?,?,?,?,?,?,?)");
          $stmtSociety->bind_param("ssssssss", $societyName, $personInChargeTelephone, $personInChargeName,
          $personInChargeSurname, $societyAddress, $societyAddressNumber, $societyPostalNumber, $societyCity);
          //Eseguo la query.
          if(!$stmtSociety->execute()){
            echo "La query di inserimento nella tabella società
            presente nella funzione saveNewUser() non funziona (riga:170).";
          }
          //Controllo se il nome del detentore é già presente nel databse.
          //Se non é presente eseguo le query d'inseriemnto nelle tre tabelle.
          if ($this->checkHolderDuplicate($emailHolder) == null) {
            //Query utilizzata per l'inserimento dei dati del detentore all'interno della tabella corretta.
            //Uso un prepared statement per evitare le SQLInjection.
            $stmtHolder = $this->conn->prepare("INSERT INTO detentore VALUES (?,?,?,?,?,?,?,?,?)");
            $stmtHolder->bind_param("sssssssis", $emailHolder, $holderName, $holderSurname, $holderAddress,
            $holderAddressNumber, $holderPostalNumber, $holderCity, $payment, $societyName);
            //Eseguo la query.
            if(!$stmtHolder->execute()){
              echo "La query di inserimento nella tabella detentore
              presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:183).";
            }
            //Controllo se il numero di targa é già presente nel databse.
            //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
            if ($this->checkCarDuplicate($plateNumber) == null) {
              //Query utilizzata per l'inserimento dei dati dell'auto all'interno della tabella corretta.
              //Uso un prepared statement per evitare le SQLInjection.
              $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
              $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
              //Eseguo la query.
              if(!$stmtCar->execute()){
                echo "La query di inserimento nella tabella auto
                presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:196).";
              }
              $this->insertNewKeyId();
            }else {
              $ret = 1;
            }
          }else {
            //Controllo se il numero di targa é già presente nel databse.
            //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
            if ($this->checkCarDuplicate($plateNumber) == null) {
              //Query utilizzata per l'inserimento dei dati dell'auto all'interno della tabella corretta.
              //Uso un prepared statement per evitare le SQLInjection.
              $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
              $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
              //Eseguo la query.
              if(!$stmtCar->execute()){
                echo "La query di inserimento nella tabella auto
                presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:213).";
              }
              $this->insertNewKeyId();
            }else {
              $ret = 1;
            }
          }
        //Se il nome della società é già presente eseguo soltanto due query di inseriemtno.
        }else {
          //Stabilisco una nuova connessione con mysqli.
          $this->newConnection();
          //Controllo se il nome del detentore é già presente nel databse.
          //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
          if ($this->checkHolderDuplicate($emailHolder) == null) {
            //Query utilizzata per l'inserimento dei dati del detentore all'interno della tabella corretta.
            //Uso un prepared statement per evitare le SQLInjection.
            $stmtHolder = $this->conn->prepare("INSERT INTO detentore VALUES (?,?,?,?,?,?,?,?, ?)");
            $stmtHolder->bind_param("sssssssis", $emailHolder, $holderName, $holderSurname, $holderAddress,
            $holderAddressNumber, $holderPostalNumber, $holderCity, $payment, $societyName);
            //Eseguo la query.
            if(!$stmtHolder->execute()){
              echo "La query di inserimento nella tabella detentore
              presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:234).";
            }
            //Controllo se il numero di targa é già presente nel databse.
            //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
            if ($this->checkCarDuplicate($plateNumber) == null) {
              //Query utilizzata per l'inserimento dei dati dell'auto all'interno della tabella corretta.
              //Uso un prepared statement per evitare le SQLInjection.
              $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
              $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
              //Eseguo la query.
              if(!$stmtCar->execute()){
                echo "La query di inserimento nella tabella auto
                presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:247).";
              }
              $this->insertNewKeyId();
            }else {
              $ret = 1;
            }
          }else {
            //Controllo se il numero di targa é già presente nel databse.
            //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
            if ($this->checkCarDuplicate($plateNumber) == null) {
              //Query utilizzata per l'inserimento dei dati dell'auto all'interno della tabella corretta.
              //Uso un prepared statement per evitare le SQLInjection.
              $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
              $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
              //Eseguo la query.
              if(!$stmtCar->execute()){
                echo "La query di inserimento nella tabella auto
                presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:264).";
              }
              $this->insertNewKeyId();
            }else {
              $ret = 1;
            }
          }
          $ret = 1;
        }
      //Se i dati relativi alla società non sono satati inseriti allora eseguo praticamente le stesse
      //query, tranne quella della società e come chiave esterna nella tabella detentore immetto null.
      }else {
        $this->insertNewKeyId();
        //Controllo se il nome del detentore é già presente nel databse.
        //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
        if ($this->checkHolderDuplicate($emailHolder) == null) {
          //Query utilizzata per l'inserimento dei dati del detentore all'interno della tabella corretta.
          //Uso un prepared statement per evitare le SQLInjection.
          $stmtHolder = $this->conn->prepare("INSERT INTO detentore VALUES (?,?,?,?,?,?,?,?,?)");
          $stmtHolder->bind_param("sssssssis", $emailHolder, $holderName, $holderSurname, $holderAddress,
          $holderAddressNumber, $holderPostalNumber, $holderCity, $payment, $null);
          //Eseguo la query.
          if(!$stmtHolder->execute()){
            echo "La query di inserimento nella tabella detentore
            presente nella funzione saveNewUser() non funziona (senza dati relativi alla società) (riga:287).";
          }
          //Controllo se il numero di targa é già presente nel databse.
          //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
          if ($this->checkCarDuplicate($plateNumber) == null) {
            //Query utilizzata per l'inserimento dei dati dell'auto nella tabella corretta.
            //Uso un prepared statement per evitare le SQLInjection.
            $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
            $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
            //Eseguo la query.
            if(!$stmtCar->execute()){
              echo "La query di inserimento nella tabella auto
              presente nella funzione saveNewUser() non funziona (riga:300).";
            }
            $this->insertNewKeyId();
          }else {
            $ret = 1;
          }
        }else {
          //Controllo se il numero di targa é già presente nel databse.
          //Se non é presente eseguo le query d'inseriemnto nelle tabelle.
          if ($this->checkCarDuplicate($plateNumber) == null) {
            //Query utilizzata per l'inserimento dei dati dell'auto nella tabella corretta.
            //Uso un prepared statement per evitare le SQLInjection.
            $stmtCar = $this->conn->prepare("INSERT INTO auto VALUES (?,?,?,?,?,?)");
            $stmtCar->bind_param("ssssss", $plateNumber, $carBrand, $carModel, $carType, $carColor, $emailHolder);
            //Eseguo la query.
            if(!$stmtCar->execute()){
              echo "La query di inserimento nella tabella auto
              presente nella funzione saveNewUser() non funziona (riga:317).";
            }
            $this->insertNewKeyId();
          }else {
            $ret = 1;
          }
        }
      }
      //Controllo che i dati vengano inseriti nella tabella temporanea una sola volta.
      if ($flag == $keysNumber) {
        //Query utilizzata per l'inserimento dei dati temporanei all'interno della tabella temporanea.
        //Uso un prepared statement per evitare le SQLInjection.
        $stmtTemp = $this->conn->prepare("INSERT INTO temporanea VALUES (?,?,?,?,?)");
        $stmtTemp->bind_param("sssii", $emailHolder, $holderName, $holderSurname, $keysNumber, $totalCost);
        //Eseguo la query.
        if(!$stmtTemp->execute()){
          echo "La query di inserimento nella tabella temporanea
          presente nella funzione saveNewUser() non funziona (con dati relativi alla società) (riga:334).";
        }
      }
      //Prima di inserire il nuovo campo nella tabella ponte (appartiene)
      //controllo che la stessa auto non sia già presente.
      //if ($this->checkCarDuplicate($plateNumber) == null) {
        //Query che permette l'estrapolazione della chiave con l'identificativo (N_chiave) maggiore.
        $stmtKeySelect = $this->conn->prepare("SELECT N_chiave FROM chiave ORDER BY N_chiave DESC LIMIT 1");
        //Eseguo la query.
        if ($stmtKeySelect->execute()) {
          $value = $stmtKeySelect->get_result();
          $result = $value->fetch_assoc();
          $temp = $result["N_chiave"];
        }else {
          echo "La query di selezione della chiave con l'Id maggiore
          presente nella funzione saveNewUser() non funziona (riga:346).";
        }
        //Prendo la data corrente.
        $timeStart = time();
        $orderTime = date('d/m/Y', $timeStart);
        //Query utilizzata per l'inserimento dei dati all'interno della tabella appartiene.
        //Uso un prepared statement per evitare le SQLInjection.
        $stmtTemp = $this->conn->prepare("INSERT INTO appartiene (Da, A, N_chiave, N_targa) VALUES (?,?,?,?);");
        $stmtTemp->bind_param("ssis", $orderTime, $null, $temp, $plateNumber);
        //Eseguo la query.
        if(!$stmtTemp->execute()){
          echo "La query di inserimento nella tabella ponte appartiene
          presente nella funzione saveNewUser() non funziona (riga:361).";
        }
    //  }
      //Se ci sono dei dati dublicati allora ritorno un 1 per avvertire l'utente.
      if ($ret == 1) {
        return 1;
      }
    }

    /**
    * Funzione che estrapola la chiave con l'id maggiore e lo incrementa di 1.
    */
    function insertNewKeyId(){
      $temp = null;
      $returned = 0;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permette l'estrapolazione della chiave con l'identificativo (N_chiave) maggiore.
      $stmtKeySelect = $this->conn->prepare("SELECT N_chiave FROM chiave ORDER BY N_chiave DESC LIMIT 1");
      //Eseguo la query, ed incremento di uno il risultato.
      if ($stmtKeySelect->execute()) {
        $value = $stmtKeySelect->get_result();
        $result = $value->fetch_assoc();
        $temp = $result["N_chiave"] + 1;
      }else {
        echo "La query di selezione della chiave con l'Id maggiore
        presente nella funzione saveNewUser() non funziona (riga:384).";
      }
      //Query che inserisce una nuova riga nella tabella "chiave",
      //questa riga contiene l'identificativo più alto incrementato di 1.
      //Uso un prepared statement per evitare le SQLInjection.
      $stmtKeyInsert = $this->conn->prepare("INSERT INTO chiave VALUES (?, ?)");
      $stmtKeyInsert->bind_param("ii", $temp, $returned);
      //Eseguo la query.
      if(!$stmtKeyInsert->execute()){
        echo "La query di inserimento nella tabella chiave
        presente nella funzione saveNewUser() non funziona (riga:397).";
      }
    }

    /**
    * Funzione che controlla se la società inserita é già presente nella tabella.
    * @return int Ritorna il risultato della query, 1 se é già presente null altrimenti.
    */
    function checkSocietyDuplicate($societyName){
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permettono l'estrapolazione dei nomi delle società,
      //utilizzando un prepared statement per evitare delle SQLInjection.
      $stmtNameSociety = $this->conn->prepare ("SELECT 1 FROM societa where Nome_societa = ?");
      $stmtNameSociety->bind_param("s", $societyName);
      //Eseguo la query.
      if($stmtNameSociety->execute()){
        //Ritorno il risultato della select.
        return $stmtNameSociety->fetch();
      }else {
        echo "La query di selezione dei nomi delle società
        presente nella funzione checkSocietyDuplicate() non funziona (riga:415).";
      }
    }

    /**
    * Funzione che controlla se l'email del detentore inserito é già presente nella tabella.
    * @return int Ritorna il risultato della query, 1 se é già presente null altrimenti.
    */
    function checkHolderDuplicate($emailHolder){
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permettono l'estrapolazione delle email del detentore,
      //utilizzando un prepared statement per evitare delle SQLInjection.
      $stmtEmailHolder = $this->conn->prepare ("SELECT 1 FROM detentore where Email = ?");
      $stmtEmailHolder->bind_param("s", $emailHolder);
      //Eseguo la query.
      if($stmtEmailHolder->execute()){
        //Ritorno il risultato della select.
        return $stmtEmailHolder->fetch();
      }else {
        echo "La query di selezione delle email dei detentori
        presente nella funzione checkHolderDuplicate() non funziona (riga:436).";
      }
    }

    /**
    * Funzione che controlla se il numero di targa inserito é già presente nella tabella.
    * @return int Ritorna il risultato della query, 1 se é già presente null altrimenti.
    */
      function checkCarDuplicate($plateNumber){
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permettono l'estrapolazione delle targhe delle auto,
      //utilizzando un prepared statement per evitare delle SQLInjection.
      $stmtPlateNumber = $this->conn->prepare ("SELECT 1 FROM auto where N_targa = ?");
      $stmtPlateNumber->bind_param("s", $plateNumber);
      //Eseguo la query.
      if($stmtPlateNumber->execute()){
        //Ritorno il risultato della select.
        return $stmtPlateNumber->fetch();
      }else {
        echo "La query di selezione delle targhe delle auto
        presente nella funzione checkCarDuplicate() non funziona (riga:457).";
      }
    }
  }
?>
