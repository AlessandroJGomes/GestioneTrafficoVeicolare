
function hide(choice) {
  var required = document.getElementsByClassName('notRequired');
  if (choice.value == "0") {
    for (var i = 0; i < required.length; i++) {
      required[i].hidden=true;
    }
  }else{
    for (var i = 0; i < required.length; i++) {
      required[i].hidden=false;
    }
  }
}
