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
    * Questa funzione si occupa di estrapolare i vari amministratori bloccati
    * all'interno del database tramite una query.
    * @return list Array di stringhe contenente tutti gli username degli amministratori bloccati.
    */
    function getBlockedAdministrator() {
      $confirmed = 0;
      $containerData = array();
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permette l'estrapolazione di tutti gli amministratori bloccati
      //utilizzando un prepare statement per evitare delle SQLInjection.
      $stmt = $this->conn->prepare ("SELECT Username FROM amministratore where Confermato = ?");
      $stmt->bind_param("i", $confirmed);
      //Eseguo la query.
      if(!$stmt->execute()){
        echo "La query di estrapolamento degli amministratori bloccati
        presente nella funzione getBlockedAdministrator() non funziona (riga:45).";
      }else {
        $result = $stmt->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerData, $row);
        }
        return $containerData;
      }
    }

    /**
    * Questa funzione si occupa di estrapolare i vari amministratori sbloccati
    * all'interno del database tramite una query.
    * @return list Array di stringhe contenente tutti gli username degli amministratori sbloccati.
    */
    function getUnBlockedAdministrator() {
      $confirmed = 1;
      $containerData = array();
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permette l'estrapolazione di tutti gli amministratori sbloccati
      //utilizzando un prepare statement per evitare delle SQLInjection.
      $stmt = $this->conn->prepare ("SELECT Username FROM amministratore where Confermato = ?");
      $stmt->bind_param("i", $confirmed);
      //Eseguo la query.
      if(!$stmt->execute()){
        echo "La query di inserimento delle credenziali dell'amministratore
        presente nella funzione getUnBlockedAdministrator() non funziona (riga:74).";
      }else {
        $result = $stmt->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerData, $row);
        }
        return $containerData;
      }
    }

    /**
    * Questa funzione si occupa dello sblocco e/o blocco dei vari amministratori
    * all'interno del database tramite una query.
    * @param state L'array contenente tutti gli username selezionati.
    * @param currentState Lo stato attuale del amministrtore (bloccato o sbloccato).
    */
    function changeAdministratorFlag($state, $currentState) {
      $unLock = 1;
      $lock = 0;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Controllo se l'array contenente gli username degli amministratori selezionati é vuoto oppure no.
      if(count($state) != 0) {
        //Ciclo l'array e divido il nome ed il cognome di ogni allievo in un'differente array.
        for ($i=0; $i < count($state); $i++) {
          //Controllo lo stato del flag se é bloccato (0) o sbloccato (1).
          if($currentState == 0) {
            //Eseguo la query che modifica lo stato d'accesso.
            $stmt = $this->conn->prepare("UPDATE amministratore set Confermato = ? where Username = ?");
            $stmt->bind_param("is", $unLock, $state[$i]);
          }else {
            //Eseguo la query che modifica lo stato d'accesso.
            $stmt = $this->conn->prepare("UPDATE amministratore set Confermato = ? where Username = ?");
            $stmt->bind_param("is", $lock, $state[$i]);
          }
          if($stmt->execute()) {
            //Richiamo le funzioni che si occupano di stampare a schermo le due tabelle degli amministratori bloccati e non.
            $this->getBlockedAdministrator();
            $this->getUnBlockedAdministrator();

          }else {
            echo "La query di di modifica del flag dell'amministratore
            presente nella funzione changeAdministratorFlag() non funziona (riga:107-111).";
          }
        }
      }
    }

    /**
    * Questa funzione si occupa dell'estrapolazione dei dati dalla teblla temporanea tramite una query.
    * @return list Array di stringhe contenente tutti i dati riguradanti le nuove richieste da confermare.
    */
    function getNewUser() {
      $containerData = array();
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che permettono l'estrapolazione dei dati riguardanti le nuove richieste,
      //utilizzando un prepare statement per evitare delle SQLInjection.
      $stmtViewNewUser = $this->conn->prepare("SELECT * FROM temporanea");
      //Eseguo la query.
      if(!$stmtViewNewUser->execute()){
        echo "La query di selezione del nome e del cognome dei detentori
        presente nella funzione getNewUser() non funziona (riga:137).";
      }else {
        $result = $stmtViewNewUser->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerData, $row);
        }
        return $containerData;
      }
    }

    /**
    * Questa funzione si occupa dell'estrapolazione dei dati dalla tabella detentore e auto tramite una join.
    * @return list Array di stringhe contenente tutti i nomi, congnomi e le varie info della propria auto di ogni utente registrato.
    */
    function getAllUsers() {
      $containerData = array();
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che estrapola il nome, il cognome e le info delle relative auto dei vari detentori.
      //Utilizzo un prepare statement per evitare delle SQLInjection.
      $stmtViewAllUsers = $this->conn->prepare("SELECT d.Nome, d.Cognome, a.N_targa, a.Marca, a.Colore, a.Modello, a.Tipo
    	FROM detentore d, auto a
    	INNER JOIN auto
    		WHERE d.Email = a.Email_detentore;
      ");
      $stmtViewAllUsers->execute();
      $result = $stmtViewAllUsers->get_result();
      //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
      while ($row = $result->fetch_assoc()) {
        array_push($containerData, $row);
      }
      return $containerData;
    }

    /**
    * Questa funzione si occupa dell'estrapolazione dei dati relativi ad ogni detentore tramite una join.
    * @return list Array di stringhe contenente tutti i nomi ed i congnomi dei vari detentori che non hanno restituito la loro chiave.
    */
    function getNewKeysReturn() {
      $containerData = array();
      $returnKey = 0;
      $paymentSuccessful = 1;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Query che utilizza una JOIN per estrapolare l'email, il nome ed il cognome dei detentori
      //che non hanno ancora restituito una chiave.
      //Utilizzo un prepare statement per evitare delle SQLInjection.
      $stmtKeysReturned = $this->conn->prepare("SELECT detentore.Email, detentore.nome, detentore.cognome
    	FROM detentore
    	INNER JOIN auto
    		ON detentore.Email = auto.Email_detentore
    	INNER JOIN appartiene
    		ON auto.N_targa = appartiene.N_targa
    	INNER JOIN chiave
    		ON appartiene.N_chiave = chiave.N_chiave
    	WHERE chiave.Restituita = ? && detentore.Pagamento_effettuato = ?;
      ");
      $stmtKeysReturned->bind_param("ii", $returnKey, $paymentSuccessful);
      $stmtKeysReturned->execute();
      $result = $stmtKeysReturned->get_result();
      //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
      while ($row = $result->fetch_assoc()) {
        array_push($containerData, $row);
      }
      return $containerData;
    }

    /**
    * Questa funzione si occupa del cambiamento del flag, tramite una join, delle varie chiavi restituite.
    * @param email L'email del detentore che restituisce la sua chiave.
    */
    function setNewKeysReturn($email) {
      $containerData = array();
      $returnKey = 0;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      if (count($email)!= 0) {
        for ($i = 0; $i < count($email); $i++) {
          //Query che utilizza una JOIN per modificare il flag di una chiave
          //appena restituita per renderla disponibile ad altri.
          //Utilizzo un prepare statement per evitare delle SQLInjection.
          $stmtKeysReturned = $this->conn->prepare("UPDATE chiave
      		INNER JOIN appartiene
      			ON chiave.N_chiave = appartiene.N_chiave
      		INNER JOIN auto
      			ON appartiene.N_targa = auto.N_targa
      		INNER JOIN detentore
      			ON auto.Email_detentore = detentore.Email
      		SET chiave.Restituita = 1
      		WHERE detentore.Email = ?;
          ");
          $stmtKeysReturned->bind_param("s", $email[$i]);
          if(!$stmtKeysReturned->execute()){
            echo "La query di modifica del flag della restituzione della chiave
            nella tabella chiave presente nella funzione setNewKeysReturn() non funziona (riga:223).";
          }
        }
      }else {
        echo "Non hai selezionato nessun utente";
      }
    }

    /**
    * Questa funzione si occupa della conferma dei pagamenti e della eliminazione
    * di quest'ultimi dalla tabella temporanea tramite delle query.
    * In fine si occupa dell'invio di una mail di conferma al nuovo utente.
    * @param email L'array contenente tutti gli username selezionati.
    */
    function confirmKeysRequest($email) {
      $paymentConfirmed = 1;
      $containerData = array();
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Controllo se l'array contenente l'email dei nuovi utenti selezionati é vuoto oppure no.
      if(count($email) != 0) {
        //Ciclo l'array.
        for ($i=0; $i < count($email); $i++) {
          //Query che permettono la modifica del flag di conferma del pagamento da parte dell'utente,
          //utilizzando un prepare statement per evitare delle SQLInjection.
          $stmtConfirm = $this->conn->prepare("UPDATE detentore set Pagamento_effettuato = ? where Email = ?");
          $stmtConfirm->bind_param("is", $paymentConfirmed, $email[$i]);
          if(!$stmtConfirm->execute()){
            echo "La query di modifica della conferma del pagamento nella tabella detentore
            presente nella funzione confirmKeysRequest() non funziona (riga:261).";
          }
          //Query che permettono l'estrapolazione del nome e cognome dell'utente, il numero di chiavi e il costo totale.
          //Utilizzando un prepare statement per evitare delle SQLInjection.
          $stmtSelect = $this->conn->prepare("SELECT Nome,Cognome,N_chiavi,Totale FROM temporanea where Email = ?");
          $stmtSelect->bind_param("s", $email[$i]);
          if(!$stmtSelect->execute()){
            echo "La query d'estrapolazione delle informazioni del detentore
            presente nella funzione confirmKeysRequest() non funziona (riga:269).";
          }else {
            $result = $stmtSelect->get_result();
            //Ciclo tutti i dati che la query mi ritorna e li inserisco in un'array.
            while ($row = $result->fetch_assoc()) {
              //preparo le variabili che servono a mandare l'email di conferma all'utente
              $to = $email[$i];
              $subject = "Conferma pagamento";
              $txt =
              "Salve ".$row['Nome']." ".$row['Cognome']."!<br>".
              "Il suo pagamento per la richiesta della chiave/i é stato ricevuto!<br>".
              "La sua chiave/i le verrà spedita a breve.<br>".
              "Qui di seguito troverà le informazioni riguardanti il suo acquisto:".
              "<ul><li>N° chiavi: ".$row['N_chiavi']."</li><li>Password: ".$row['Totale']."</li></ul>";
              $headers =  'MIME-Version: 1.0' . "\r\n";
              $headers .= 'From: gestionetrafficoveicolare@gmail.com'. "\r\n";
              $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
              //invio l'email
              mail($to,$subject,$txt, $headers);
            }
          }
          //Query che permettono la cancellazione delle informazioni presenti nella tabella temporanea,
          //utilizzando un prepare statement per evitare delle SQLInjection.
          $stmtDelete = $this->conn->prepare("DELETE FROM temporanea WHERE Email = ?");
          $stmtDelete->bind_param("s", $email[$i]);
          if(!$stmtDelete->execute()){
            echo "La query di concellazione nella tabella temporanea
            presente nella funzione confirmKeysRequest() non funziona (riga:296).";
          }
          //Richiamo le funzioni che si occupano di stampare a schermo le due tabelle degli amministratori bloccati e non.
          $this->getNewUser();
        }
      }else {
        echo "Non hai selezionato nessun utente";
      }
    }

    /**
    * Questa funzione si occupa di salvare il contenuto di un file csv
    * all'interno della tabella statistiche in cui saranno presenti tutte
    * le targhe delle auto che sono passate dalla sbarra.
    * @param fileName L'array contenente tutti i dati del file csv.
    */
    function csvImport($fileName){
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      //Prendo la data corrente.
      $timeStart = time();
      $orderTime = date('d/m/Y', $timeStart);
      $file = fopen($fileName, "r");
      while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
        //Query che permettono l'inserimento dei numeri di targa nella tabella statistiche.
        //Utilizzando un prepare statement per evitare delle SQLInjection.
        $stmtInsert = $this->conn->prepare("INSERT into statistiche (N_targa, Data) values (?,?)");
        $stmtInsert->bind_param("ss", $column[2], $orderTime);
        if(!$stmtInsert->execute()){
          echo "La query d'inserimento del contenuto del file CSV
          presente nella funzione csvImport() non funziona (riga:320).";
        }
        //Query che permettono l'eliminazione dei dati che non mi interessano.
        //Utilizzando un prepare statement per evitare delle SQLInjection.
        $stmtDelete = $this->conn->prepare("DELETE FROM statistiche WHERE N_targa = 'plate_numb'");
        if(!$stmtDelete->execute()){
          echo "La query di eliminazione della prima riga del file CSV
          presente nella funzione csvImport() non funziona (riga:327).";
        }
      }
    }

    /**
    * Questa funzione si occupa di eseguire la statistica sul numero di volte
    * in cui un'auto é passata da quella barriera.
    * @return array L'array contenente tutti i dati relativi alla statistica.
    */
    function getStatistics(){
      //Creazione delle variabili necessarie.
      $containerAll = array();
      $containerPlate = array();
      $containerNumberOfPassagesForCar = array();
      $containerTemp = array();
      $counter = 0;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      $stmtSelectDistinctPlates = $this->conn->prepare("SELECT DISTINCT N_targa from statistiche");
      //Eseguo la query.
      if(!$stmtSelectDistinctPlates->execute()){
        echo "La query di estrapolamento delle varie targhe per le statistiche
        presente nella funzione getStatistics() non funziona (riga:350).";
      }else {
        $result = $stmtSelectDistinctPlates->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerPlate, $row);
        }
      }
      $stmtSelectAllPlate = $this->conn->prepare("SELECT N_targa from statistiche");
      //Eseguo la query.
      if(!$stmtSelectAllPlate->execute()){
        echo "La query di estrapolamento delle varie targhe per la prima statistica
        presente nella funzione getStatistics() non funziona (riga:362).";
      }else {
        $result = $stmtSelectAllPlate->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerTemp, $row);
        }
      }
      //Eseguo un confronto tra i due array contenenti le targhe ed a ogni match incremento
      //un contatore.
      for ($i = 0; $i < count($containerPlate); $i++) {
        for ($j = 0; $j < count($containerTemp); $j++) {
          if ($containerPlate[$i] == $containerTemp[$j]) {
            $counter++;
          }
        }
        array_push($containerNumberOfPassagesForCar, $counter);
        $counter = 0;
      }
      array_push($containerAll, $containerPlate);
      array_push($containerAll, $containerNumberOfPassagesForCar);
      return $containerAll;
    }

    /**
    * Questa funzione si occupa di redigere la seconda stima della pagina delle
    * statistiche, cioé il conteggio totale delle auto per ogni società.
    * @return list Array di stringhe contenente la lista della seconda statistica.
    */
    function getSocietyStatistics() {
      $containerData = array();
      $containerSocietyName = array();
      $containerEmailHolder = array();
      $containerPlateNumber = array();
      $containerSocietyStatistics = array();
      $containerTemp = array();
      $counter = 0;
      $total = 0;
      //Stabilisco una nuova connessione con mysqli.
      $this->newConnection();
      // Query che mi permette di estrapolare tutti i numeri di targa presenti nella tabella statistiche,
      //utilizzando un prepare statement per evitare delle SQLInjection.
      $stmtSelectAllPlate = $this->conn->prepare("SELECT N_targa from statistiche");
      //Eseguo la query.
      if(!$stmtSelectAllPlate->execute()){
        echo "La query di estrapolamento delle varie targhe per la seconda statistica
        presente nella funzione getSocietyStatistics() non funziona (riga:413).";
      }else {
        $result = $stmtSelectAllPlate->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerTemp, $row);
        }
      }
      //Query che permette l'estrapolazione di tutti i nomi delle società,
      //utilizzando un prepare statement per evitare delle SQLInjection.
      $stmtNameSociety = $this->conn->prepare ("SELECT Nome_societa FROM societa");
      //Eseguo la query.
      if(!$stmtNameSociety->execute()){
        echo "La query di estrapolazione dei nomi delle società
        presente nella funzione getSocietyStatistics() non funziona (riga:428).";
      }else {
        $result = $stmtNameSociety->get_result();
        //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
        while ($row = $result->fetch_assoc()) {
          array_push($containerSocietyName, $row);
        }
      }
      //Ciclo tutti i nomi della società, per ogni nome vado ad estrapolare tutti i suoi detentori
      //ed in seguito, avendo tutti i detentori di ogni società, estrapolo tutte le auto che fanno parte
      //di ogni società.
      for ($i = 0; $i < count($containerSocietyName); $i++) {
        //Query che permette l'estrapolazione di tutte le email di tutti i detentori in base al nome della società
        //utilizzando un prepare statement per evitare delle SQLInjection.
        $stmtEmailHolder = $this->conn->prepare ("SELECT Email FROM detentore where Nome_societa = ?");
        $stmtEmailHolder->bind_param("s", $containerSocietyName[$i]);
        //Eseguo la query.
        if(!$stmtEmailHolder->execute()){
          echo "La query di estrapolazione delle email dei vari detentori
          presente nella funzione getSocietyStatistics() non funziona (riga:446).";
        }else {
          $result = $stmtEmailHolder->get_result();
          //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
          while ($row = $result->fetch_assoc()) {
            array_push($containerEmailHolder, $row);
          }
          //Ciclo tutte le email, per ogniuna di essa vado ad estrapolare il suo corrispettivo numero di targa.
          for ($j = 0; $j < count($containerEmailHolder); $j++) {
            //Query che permette l'estrapolazione di tutti gli amministratori sbloccati
            //utilizzando un prepare statement per evitare delle SQLInjection.
            $stmtPlateNumber = $this->conn->prepare ("SELECT N_targa FROM auto where Email_detentore = ?");
            $stmtPlateNumber->bind_param("s", $containerEmailHolder[$j]);
            //Eseguo la query.
            if(!$stmtPlateNumber->execute()){
              echo "La query di estrapolazione dei numeri di targa delle varie auto
              presente nella funzione getSocietyStatistics() non funziona (riga:461).";
            }else {
              $result = $stmtPlateNumber->get_result();
              //Ciclo tutti i "dati" che la query mi ritorna e li inserisco in un'array.
              while ($row = $result->fetch_assoc()) {
                array_push($containerPlateNumber, $row);
              }
            }
          }
          //Ciclo tutte le targhe, ognuna di essa la vad a confrontare con le targhe presenti nella tabella statistica
          //ed a ogni match vado ad eseguire una somma dei vari valori che il contatore assume e ritornero il totale per ogni società.
          for ($j = 0; $j < count($containerPlateNumber); $j++) {
            for ($z = 0; $z < count($containerTemp); $z++) {
              if ($containerPlate[$j] == $containerTemp[$z]) {
                $counter++;
              }
            }
            $total += $counter;
            $counter = 0;
          }
          array_push($containerSocietyStatistics, $total);
          $total = 0;
        }
      }
      array_push($containerData, $containerSocietyName);
      array_push($containerData, $containerSocietyStatistics);
      return $containerData;
    }
  }
?>
