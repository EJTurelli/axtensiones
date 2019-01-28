function CAxtension() {
	this.cidnum = '';
  	this.cidname = '';

  	this.clonar = function(dato) {
  		this.cidnum = dato.cidnum;
  		this.cidname = dato.cidname;
  	}
};

function CAxtensiones() {
	this.lista = new Array();

	this.clonar = function(dato) {
		this.lista.splice (0, this.lista.length);

	    for (var i in dato.lista) {
	      var j = this.lista.length;
	      this.lista[j] = new CAxtension();
	      this.lista[j].clonar(dato.lista[i]);
	    }
	};

	this.getDisplay = function() {
		var color;
		var icono;
		var texto = "";
		var retorno = '<table class="table table-bordered">';

		for (var i in this.lista) {
			var status = gStatus.getStatus(this.lista[i].cidnum);

			if (status.cidnum == this.lista[i].cidnum) {
				if (status.estado == "Llamando") {
					color = "primary";
					icono = "glyphicon glyphicon-bell";
				} 
				else {
					if (status.origen == "Si") {
						color = "danger";
					}
					else {
						color = "warning";
					} 		

					icono = "glyphicon glyphicon-earphone";
				}
				texto = this.lista[i].cidname+' hablando con el número: '+status.numero+' durante '+status.tiempo+' segundos';
			}
			else {
				color = "default";
				icono = "glyphicon glyphicon-phone-alt";
				texto = this.lista[i].cidname;
			}

			retorno += '<button class="btn btn-'+color+' btn-sm" data-toggle="tooltip" data-placement="top" title="'+texto+'"><span class="'+icono+'"></span> '+this.lista[i].cidnum+'</button>';

		}

		retorno += '</table>';
		retorno += '<script>';
		retorno += '$("[data-toggle=\'tooltip\']").tooltip();';
		retorno += '</script>';
		
		return retorno;
	};

};


function CStatus() {
	this.cidnum = '';
  	this.numero = '';
  	this.tiempo = '';
  	this.origen = '';
  	this.estado = '';

  	this.clonar = function(dato) {
  		this.cidnum = dato.cidnum;
  		this.numero = dato.numero;
  		this.tiempo = dato.tiempo;
  		this.origen = dato.origen;
  		this.estado = dato.estado;
  	}
};

function CAxtensionesStatus() {
	this.lista = new Array();

	this.clonar = function(dato) {
		this.lista.splice (0, this.lista.length);

	    for (var i in dato.lista) {
	      var j = this.lista.length;
	      this.lista[j] = new CStatus();
	      this.lista[j].clonar(dato.lista[i]);
	    }
	};

	this.getStatus = function(cidnum) {
		var tmp = new CStatus();

	    for (var i in this.lista) {
	    	if (this.lista[i].cidnum == cidnum) {
	    		return this.lista[i];
	    	}
	    }

	    return tmp;
	};

};


