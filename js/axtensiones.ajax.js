function cargarExtensiones(destino) {
  var data = {
    "action": "cargar_extensiones"
  };
  
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "axtensiones.json.php", 
    data: data,
    success: function(data) {
      destino.clonar (JSON.parse(data["json"]));
      $('#lista').html(destino.getDisplay());
    },
    error: function(data) {
      alert ("Error: "+JSON.stringify(data));
    }
  });

  return false;
}

function cargarStatus(destino, objetivo) {
  var data = {
    "action": "cargar_status"
  };
  
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "axtensiones.json.php", 
    data: data,
    success: function(data) {
      destino.clonar (JSON.parse(data["json"]));
      $('#lista').html(objetivo.getDisplay());

      //$('#debug').html('<pre>'+JSON.stringify(destino)+'</pre>');
    },
    error: function(data) {
      alert ("Error: "+JSON.stringify(data));
    }
  });

  return false;
}
