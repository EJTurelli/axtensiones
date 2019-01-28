<!DOCTYPE html>
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Axtensiones</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/bootstrapValidator.min.css">

  <script src="js/axtensiones.js"></script>
  <script src="js/axtensiones.ajax.js"></script>
</head>

<body onload="startTime()">
  &nbsp;
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-body">
        <!--h4>Axtensiones v1.0</h4-->
        <div id="lista"></div>
      </div>
    </div>
    <div id="debug"></div>
  </div> <!--class="container-fluid"-->

  <script src="js/jquery-1.11.3.min.js"></script>
  <script src="js/jquery-migrate-1.2.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrapValidator.min.js"></script>

  <script>
    var gExtensiones = new CAxtensiones();
    var gStatus = new CAxtensionesStatus();
    cargarExtensiones(gExtensiones);
    cargarStatus(gStatus, gExtensiones);

    function startTime() {
      cargarStatus(gStatus, gExtensiones);
      t = setTimeout(function(){ startTime() }, 2000);
    }
  </script>

</body>

</html>

