<?php
require_once('lib/php-asmanager.php');

if (is_ajax()) {
	if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
		$action = $_POST["action"];
		switch($action) { //Switch case for value of action
			case "cargar_extensiones": cargar_extensiones_function(); break;
			case "cargar_status": cargar_status_function(); break;
		}
	}
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

class CAxtension {
	public $cidnum;
	public $cidname;

	public function __construct ($cidnum, $cidname) {
		$this->cidnum = $cidnum;
		$this->cidname = $cidname;
	}
}

class CAxtensiones {
	public $lista = array();

	public function addAxtension ($cidnum, $cidname) {
		array_push($this->lista, new CAxtension ($cidnum, $cidname));
	}
}


function cargar_extensiones_function() {
	$ami = new AGI_AsteriskManager();
	$resultado= $ami->connect("localhost", "usuario", "clave");

	$data = new CAxtensiones;

	$databaseUser=$ami->database_show('AMPUSER');
	
	foreach ($databaseUser as $key => $value) {

		$cidnum = explode("/", $key)[2];
	  	if (strstr($key, $cidnum."/cidname")) {
			$data->addAxtension($cidnum, $value);
	    }
	}

	$return["json"] = json_encode($data);

	echo json_encode($return);

	$ami->disconnect();
}

class CStatus {
	public $cidnum;
	public $numero;
	public $tiempo;
	public $origen;
	public $estado;

	public function __construct ($cidnum, $numero, $tiempo, $origen, $estado) {
		$this->cidnum = $cidnum;
		$this->numero = $numero;
		$this->tiempo = $tiempo;
		$this->origen = $origen;
		$this->estado = $estado;
	}
}

class CAxtensionesStatus {
	public $lista = array();

	public function addStatus ($cidnum, $numero, $tiempo, $origen, $estado) {
		array_push($this->lista, new CStatus ($cidnum, $numero, $tiempo, $origen, $estado));
	}
}

function buscarNumero ($id, $lista) {

	foreach($lista as $value) {
		$tmp2=explode("!", $value);

		if (strcmp($tmp2[0], $id) == 0) {
			return $tmp2[7];
		}
	}

	return "n/a";
}


function cargar_status_function() {
	$ami = new AGI_AsteriskManager();
	$resultado= $ami->connect("localhost", "usuario", "clave");

	$data = new CAxtensionesStatus;

	$tmp=$ami->Command("core show channels concise");

	$tmp1=explode("\n",$tmp['data']);
	array_shift($tmp1);
	
	foreach($tmp1 as $key => $value) {
		$tmp2=explode("!",$value);

		//if ($tmp2[0]) file_put_contents('dato.log', "Inicio ------------------\n".print_r($tmp2, true), FILE_APPEND);

		if (substr($tmp2[0], 0, 3) == "SIP") {  // Extension
			if ($tmp2[5] == "Dial") { 			// LLama
				$cidnum = $tmp2[7];

	  			if (strstr($tmp2[6], "SIP")) {  // a Interno
	  				$numero = substr(explode("/", $tmp2[6])[1], 0, 3);
	  			}
	  			else if (strstr($tmp2[6], "PBX-Router")) { // a Externo
	  				$numero = explode(",",  explode("/", $tmp2[6] )[2] )[0];
	  			}
	  			else {
	  				$numero = "n/a";
	  			} 
	  			$tiempo = $tmp2[11];

	  			if (strstr($tmp2[4], "Ring")) {  
	  				$estado = "Llamando";
	  			}
	  			else {  
	  				$estado = "Hablando";
	  			}

				$data->addStatus ($cidnum, $numero, $tiempo, "Si", $estado);
			}
			else if ($tmp2[5] == "AppDial") {	// Recibe 
				$cidnum = $tmp2[7];

	  			if (strstr($tmp2[12], "SIP")) {  // a Interno
	  				$numero = substr(explode("/", $tmp2[12])[1], 0, 3);
	  			}
	  			else if (strstr($tmp2[12], "Trunk-PBX-Router")) {
	  				$numero = buscarNumero ($tmp2[12], $tmp1);
	  			} 
	  			else {
	  				$numero = "n/a";
	  			} 

	  			$tiempo = $tmp2[11];

	  			if (strstr($tmp2[4], "Ring")) {  
	  				$estado = "Llamando";
	  			}
	  			else {  
	  				$estado = "Hablando";
	  			}
	  			
				$data->addStatus ($cidnum, $numero, $tiempo, "", $estado);
			}
		
			//file_put_contents('dato.log', "\nData: ".print_r($data, true)."\nFin ---------------------\n\n", FILE_APPEND);				
		}
	}
	
	$return["json"] = json_encode($data);

	echo json_encode($return);

	$ami->disconnect();
}


/*

Interno llama a Interno ó a Externo
0- SIP
4- Ring
5- Dial
6- SIP/numero ó PBX-Router/numero
7- cidnum
11-tiempo

Interno hablando con Interno ó Externo (origen)
0- SIP
4- Up
5- Dial
6- SIP/numero ó PBX-Router/numero
7- cidnum
11-tiempo


Interno recibe de Interno ó de Externo
0- SIP
4- Ringing
5- AppDial
7- cidnum
11-tiempo
numero: n/a

Interno hablando con Interno ó Externo (destino)
0- SIP
4- Up
5- AppDial
7- cidnum
11-tiempo
12-SIP/numero o n/a



*/