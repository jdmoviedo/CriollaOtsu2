var urlBase = rutaActual();
function rutaActual(){
	var partesrutaactual = window.location.pathname.split('/');
	var rutaactual = "";
	for (i = 0; i < partesrutaactual.length - 1; i++) {
		rutaactual += partesrutaactual[i];
		rutaactual += "/";
	}
	var port = "";//window.location.port != "" ? ":"+window.location.port : "";
	return window.location.protocol+"//"+window.location.hostname+port+rutaactual;
}

var paginaBase = paginaActual();
function paginaActual(){
	var port = "";//window.location.port != "" ? ":"+window.location.port : "";
	return window.location.protocol+"//"+window.location.hostname+"/bernardino_v2/";
}