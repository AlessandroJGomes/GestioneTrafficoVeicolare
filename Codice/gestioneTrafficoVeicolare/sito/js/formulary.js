// Funzione che nasconde e disabilita tutti gli input della società in base
// alla scleta fatta dall'utente nella pagina formulary.php tramite i due
// radio buttun che stabiliscono se si possiede o si faccia parte di una
// società.
function hide(choice) {
  var hide = document.getElementById('society');
  var required = document.getElementsByClassName('notRequired');
  if (choice.value == "0") {
    for (var i = 0; i < required.length; i++) {
      required[i].disabled=true;
    }
    hide.hidden=true;
  }else{
    for (var i = 0; i < required.length; i++) {
      required[i].disabled=false;
    }
    hide.hidden=false;
  }
}

//Creo delle variabili contenenti un'espressione regolare.
var telephoneNumberRegex = /^[+]{0,1}[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
var capRegex = /^[0-9]{4}$/;
var plateRegex = /^[A-Z]{2}[0-9]{1,6}$/;
// Questa funzione controlla, grazie a delle espressioni regolari,
// se l'utente inserisce i dati nel modo corretto.
function control(element, regex) {
  var submit = document.getElementById("orderButton");
  if (regex.test(element.value)) {
    element.style.color = "black";
    submit.disabled = false;
  }else {
    element.style.color = "red";
    submit.disabled = true;
  }
}

// Funzione che incrementa il numero di chiavi che l'utente vuole ordinare.
// Questa funzione crea anche una copia, per ogni volta che si clicca il bottone
// aggiungi chiave, di tutti gli input riguardanti le info dell'utente.
function incrementKeys(){
  // Incremento del numero di chiavi
  var value = parseInt(document.getElementById('inputNumberKeys').value, 10);
  value = isNaN(value) ? 1 : value;
  value++;
  document.getElementById('inputNumberKeys').value = value;

  // Copia dei vari input.
  /*Creazione del fieldset (contenitore generale)*/
  var fieldset = document.createElement("fieldset");
  fieldset.setAttribute("id", "detentore_" + value);
  /*Creazione del legend e attaccamento all'elemento superiore*/
  var legend = document.createElement("legend");
  legend.appendChild(document.createTextNode("Dati detentore"));
  fieldset.appendChild(legend);

  /*Creazione del primo divFormRow (prima riga dei vari input)*/
  var divFormRow = document.createElement("div");
  divFormRow.setAttribute("class", "form-row");

  /*Creazione del div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label del nome*/
  var label = document.createElement("label");
  label.setAttribute("for", "nameHolder");
  label.appendChild(document.createTextNode("Nome*"));
  div.appendChild(label);
  /*Creazione dell'input del nome*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputNameHolder_" + value);
  input.setAttribute("placeholder", "Nome detentore");
  input.setAttribute("required", "true");
  input.setAttribute("value", "Nome");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del secondo div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label del cognome*/
  var label = document.createElement("label");
  label.setAttribute("for", "surnameHolder");
  label.appendChild(document.createTextNode("Cognome*"));
  div.appendChild(label);
  /*Creazione dell'input del cognome*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputSurnameHolder_" + value);
  input.setAttribute("placeholder", "Cognome detentore");
  input.setAttribute("required", "true");
  input.setAttribute("value", "Cognome");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del terzo div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label della via*/
  var label = document.createElement("label");
  label.setAttribute("for", "holderAddress");
  label.appendChild(document.createTextNode("Via*"));
  div.appendChild(label);
  /*Creazione dell'input della via*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputHolderAddress_" + value);
  input.setAttribute("placeholder", "Via");
  input.setAttribute("required", "true");
  input.setAttribute("value", "Via");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del quarto div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label del numero civico*/
  var label = document.createElement("label");
  label.setAttribute("for", "holderAddressNumber");
  label.appendChild(document.createTextNode("N° Civico*"));
  div.appendChild(label);
  /*Creazione dell'input del numero civico*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputHolderAddressNumber_" + value);
  input.setAttribute("placeholder", "N° Civico");
  input.setAttribute("required", "true");
  input.setAttribute("value", "ncivico");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del quinto div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label del CAP*/
  var label = document.createElement("label");
  label.setAttribute("for", "holderPostalCode");
  label.appendChild(document.createTextNode("CAP*"));
  div.appendChild(label);
  /*Creazione dell'input del CAP*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("onkeyup", "controlSociety(this, capRegex)");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputHolderPostalNumber_" + value);
  input.setAttribute("placeholder", "CAP");
  input.setAttribute("required", "true");
  input.setAttribute("value", "6900");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del sesto div e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione del label della città*/
  var label = document.createElement("label");
  label.setAttribute("for", "holderCity");
  label.appendChild(document.createTextNode("Città*"));
  div.appendChild(label);
  /*Creazione dell'input della città*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputHolderCity_" + value);
  input.setAttribute("placeholder", "Città");
  input.setAttribute("required", "true");
  input.setAttribute("value", "citta");
  div.appendChild(input);
  divFormRow.appendChild(div);
  fieldset.appendChild(divFormRow);
  /*Fine del primo divFormRow (prima riga)*/

  /*Creazione del secondo divFormRow (seconda riga)*/
  var divFormRow = document.createElement("div");
  divFormRow.setAttribute("class", "form-row");

  /*Creazione del primo div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label dell'email*/
  var label = document.createElement("label");
  label.setAttribute("for", "emailHolder");
  label.appendChild(document.createTextNode("Email*"));
  div.appendChild(label);
  /*Creazione dell'input dell'email*/
  var input = document.createElement("input");
  input.setAttribute("type", "email");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputEmailHolder_" + value);
  input.setAttribute("placeholder", "Email detentore");
  input.setAttribute("required", "true");
  input.setAttribute("value", "a.a@a.a");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del secondo div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label della marca*/
  var label = document.createElement("label");
  label.setAttribute("for", "carBrand");
  label.appendChild(document.createTextNode("Marca veicolo*"));
  div.appendChild(label);
  /*Creazione dell'input della marca*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputCarBrand_" + value);
  input.setAttribute("placeholder", "Marca veicolo");
  input.setAttribute("required", "true");
  input.setAttribute("value", "Marca");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del terzo div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label del colore*/
  var label = document.createElement("label");
  label.setAttribute("for", "carColor");
  label.appendChild(document.createTextNode("Colore*"));
  div.appendChild(label);
  /*Creazione dell'input del colore*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputCarColor_" + value);
  input.setAttribute("placeholder", "Colore veicolo");
  input.setAttribute("required", "true");
  input.setAttribute("value", "colore");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del quarto div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label del modello*/
  var label = document.createElement("label");
  label.setAttribute("for", "carModel");
  label.appendChild(document.createTextNode("Modello veicolo*"));
  div.appendChild(label);
  /*Creazione dell'input del modello*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputCarModel_" + value);
  input.setAttribute("placeholder", "Modello veicolo");
  input.setAttribute("required", "true");
  input.setAttribute("value", "modello");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del quinto div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label del tipo*/
  var label = document.createElement("label");
  label.setAttribute("for", "carType");
  label.appendChild(document.createTextNode("Tipo veicolo*"));
  div.appendChild(label);
  /*Creazione dell'input del tipo*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputCarType_" + value);
  input.setAttribute("placeholder", "Modello veicolo");
  input.setAttribute("required", "true");
  input.setAttribute("value", "tipo");
  div.appendChild(input);
  divFormRow.appendChild(div);

  /*Creazione del sesto div della seconda riga e concatenamento di tutto il suo contenuto*/
  var div = document.createElement("div");
  div.setAttribute("class", "form-group col-md-2");
  /*Creazione label della targa*/
  var label = document.createElement("label");
  label.setAttribute("for", "plateNumber");
  label.appendChild(document.createTextNode("N° targa*"));
  div.appendChild(label);
  /*Creazione dell'input del numero di targa*/
  var input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("onkeyup", "controlSociety(this, plateRegex)");
  input.setAttribute("class", "form-control");
  input.setAttribute("name", "inputPlateNumber_" + value);
  input.setAttribute("placeholder", "N° Targa");
  input.setAttribute("required", "true");
  input.setAttribute("value", "ntarga");
  div.appendChild(input);
  divFormRow.appendChild(div);
  fieldset.appendChild(divFormRow);
  /*Fine del contenitore*/

  document.getElementById("detentori").appendChild(fieldset);


}
