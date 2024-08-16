$(document).ready(function () {
  fechaHora();
});

// Estableciendo fecha hora actual para mostrar
var dia_semana = new Array();
dia_semana[0] = "Domingo";
dia_semana[1] = "Lunes";
dia_semana[2] = "Martes";
dia_semana[3] = "Miercoles";
dia_semana[4] = "Jueves";
dia_semana[5] = "Viernes";
dia_semana[6] = "Sabado";

var mes_anio = new Array();
mes_anio[1] = "Enero";
mes_anio[2] = "Febrero";
mes_anio[3] = "Marzo";
mes_anio[4] = "Abril";
mes_anio[5] = "Mayo";
mes_anio[6] = "Junio";
mes_anio[7] = "Julio";
mes_anio[8] = "Agosto";
mes_anio[9] = "Septiembre";
mes_anio[10] = "Octubre";
mes_anio[11] = "Noviembre";
mes_anio[12] = "Diciembre";

//obtiene los datos del DP dependiendo si recibe rango true, regresa un rango
//si no regresa solo la fecha del idInicial
//si recibe true en requerido muestra una validacion sobre el DP indicado
function obtenervaloresdatepicker(tipo, idInicial, idFinal, requerido) {
  /*tipos: 1-fecha
	2-rango
	3-multiple
	4-año mes*/

  var fechaRetorno = "";
  var mensaje = "";
  var fechaInicial = "";
  var fechaFinal = "";
  switch (tipo) {
    case 1:
      if ($("#" + idInicial).val() != "") {
        if (requerido == true) {
          mensaje = "Selecciona una fecha";
          mostrarmensajevalidaciondatepicker(idInicial, mensaje);
        } else {
          fechaInicial = moment(
            $("#" + idInicial).datepicker("getDate")
          ).format("YYYY-MM-DD");
          fechaRetorno = fechaInicial;
        }
      }
      break;
    case 2:
      if ($("#" + idInicial).val() == "") {
        if (requerido == true) {
          mensaje = "Selecciona una fecha inicial";
          mostrarmensajevalidaciondatepicker(idInicial, mensaje);
        }
      } else if ($("#" + idFinal).val() == "") {
        if (requerido == true) {
          mensaje = "Selecciona una fecha final";
          mostrarmensajevalidaciondatepicker(idFinal, mensaje);
        }
      } else {
        fechaInicial = moment($("#" + idInicial).datepicker("getDate")).format(
          "YYYY-MM-DD"
        );
        fechaFinal = moment($("#" + idFinal).datepicker("getDate")).format(
          "YYYY-MM-DD"
        );
        fechaRetorno = fechaInicial + "," + fechaFinal;
      }
      break;
    case 3:
      if ($("#" + idInicial).val() == "") {
        if (requerido == true) {
          mensaje = "Selecciona mínimo una fecha";
          mostrarmensajevalidaciondatepicker(idInicial, mensaje);
        }
      } else {
        var arrayFechas = $("#" + idInicial).datepicker("getDates");
        for (var i = 0; i < arrayFechas.length; i++) {
          fechaRetorno += moment(arrayFechas[i]).format("YYYY-MM-DD") + ",";
        }
        fechaRetorno = fechaRetorno.slice(0, -1);
      }
      break;
    case 4:
      if ($("#" + idInicial).val() != "") {
        if (requerido == true) {
          mensaje = "Selecciona una fecha";
          mostrarmensajevalidaciondatepicker(idInicial, mensaje);
        } else {
          fechaInicial = moment(
            $("#" + idInicial).datepicker("getDate")
          ).format("YYYY-MM");
          fechaRetorno = fechaInicial;
        }
      }
      break;
    case 5:
      if ($("#" + idInicial).val() != "") {
        if (requerido == true) {
          mensaje = "Selecciona una fecha";
          mostrarmensajevalidaciondatepicker(idInicial, mensaje);
        } else {
          fechaInicial = moment(
            $("#" + idInicial).datepicker("getDate")
          ).format("YYYY");
          fechaRetorno = fechaInicial;
        }
      }
      break;
  }
  return fechaRetorno;
}

//muestra un mensaje de validacion sibre el DP indicado
function mostrarmensajevalidaciondatepicker(idElemento, mensaje) {
  $("#" + idElemento).addClass("required");
  $("#" + idElemento)
    .siblings("span")
    .text(mensaje);
  $("#" + idElemento)
    .parents(".tooltipsdatepicker")
    .find("span")
    .css("display", "block");
  $("html,body").animate(
    {
      scrollTop: $("#" + idElemento).position,
    },
    200,
    "swing",
    function () {
      $("#" + idElemento)
        .parents(".tooltipsdatepicker")
        .find("span")
        .focus();
    }
  );
}
//esta funcion muestra el date picker en el input hermano
//del icono asociado al input por input group
function mostrardatepicker(elemento) {
  $(elemento).prev("a").children(":input").datepicker("show");
}
//borra las validaciones de los date picker en elos eventos estabelcidos
$(".validarDP").bind("change click mouseleave", function (e) {
  if ($(this).is("select") == true) {
    $(this).siblings("span").removeClass("required");
    $(this).siblings(".tooltipsdatepicker").find("span").fadeOut();
  } else {
    $(this).removeClass("required");
    $(this).parents(".tooltipsdatepicker").find("span").fadeOut();
  }
});

//esta funcion muestra el dp sencillo desde el icono del input text
function mostrar_dp_simple(elemento) {
  $(elemento).siblings("input:text").datepicker("show");
}

function fechaHora() {
  var hoy = new Date();
  var hora = hoy.getHours();
  var minutos = hoy.getMinutes();
  var segundos = hoy.getSeconds();

  if (hora < 10) {
    hora = "0" + hora;
  }
  if (minutos < 10) {
    minutos = "0" + minutos;
  }

  if (segundos < 10) {
    segundos = "0" + segundos;
  }

  fecha_actual =
    dia_semana[hoy.getDay()] +
    ", " +
    hoy.getDate() +
    " de " +
    mes_anio[hoy.getMonth() + 1] +
    " de " +
    hoy.getFullYear();
  hora_actual = hora + ":" + minutos + ":" + segundos;

  setTimeout(function () {
    $("#fechaHora").html(
      '<i class="ik ik-clock"> ' + fecha_actual + " " + hora_actual
    );
    fechaHora();
  }, 1000);
}
