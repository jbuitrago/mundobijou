  function NumeroegistroVerFiltros(e, cadena) {
    var evt = window.event ? window.event : e;
    var keyCode = evt.keyCode ? evt.keyCode : e.which;
    if (
        e.keyCode == 46 // delete
        || e.keyCode == 8  // backspace
        || e.keyCode == 9  // tab
        || e.keyCode == 35 // end
        || e.keyCode == 36 // home
        || e.keyCode == 37 // left arrow
        || e.keyCode == 39 // right arrow
        ) {
    } else {
        if (cadena.indexOf(String.fromCharCode(keyCode)) == -1) {
            e.preventDefault();
        }
    }
  }
