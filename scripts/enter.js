function pulsaenter(e, id) {
	var esIE = document.all;
	var esNS = document.layers;
	tecla = esIE ? event.keyCode : e.which;
	if (tecla == 13) {
		switch (id) {
			case "filtroConcepto":
				$("#btnMdalFiltroPorBuscar").trigger("click");
			break;
			case "frmLoginInTxtUsuario":
				login("formLogin");
			break;
			case "frmLoginInTxtContrasenia":
				login("formLogin");
			break;
			case "filtroRegistroOsteo":
				$("#modalFiltroRegistroOsteo").modal("hide");
				filtrar_registro(1);
			break;
			case "filtroRegistroEnvioCmAdminPLan":
				$("#modalFiltro").modal("hide");
				filtrar_registro(1);
			break;
			case "filtroRegistroEnvioCm":
				$("#modalFiltro").modal("hide");
				filtrar_registro(1);
			break;
			case "filtroRegistroTomaFirmaUsuarios":
				$("#modalFiltro").modal("hide");
				filtrar_registro(1);
			break;
			case "filtroRegistroListado":
				$("#modalFiltroRegistroListado").modal("hide");
				filtrar_registro(1);
			break;
			case "filtroRegistroListado1":
				$("#modalFiltroRegistroListado1").modal("hide");
				filtrar_registro1(1);
			break;
			case "filtroRegistroListado1":
				$("#modalFiltroRegistroListado1").modal("hide");
				filtrar_registro1(1);
			break;
			case "proformaImpresion":
				$("#modalImpresionProforma").modal("hide");
				impresion();
			break;
			case "filtroRegistro":
				$("#modalFiltroRegistro").modal("hide");
				filtrar_registro(1);
			break;
			case "cedula":
				buscar();
			break;
			case "cedulaConsulta":
				consultar_cedula();
			break;
			default:
				// Code
			break;
		}
	}
}
