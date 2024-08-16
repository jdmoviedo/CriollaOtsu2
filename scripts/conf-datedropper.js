var formatos = ["F/d/Y", "l, d de F del Y", "F del Y", "Y"];

//obtiene los datos del datedropper
function obtenerFechaDateDropper(
  tipo,
  idInicial,
  idFinal,
  requerido,
  clase = 1
) {
  var identificador = "#";
  switch (clase) {
    case 2:
      identificador = ".";
      break;
    case 3:
      identificador = "";
      idInicial = 'input[name="' + idInicial + '"]';
      idFinal = 'input[name="' + idFinal + '"]';
      break;
    default:
      break;
  }
  var fechaRetorno = "";
  switch (tipo) {
    case 1:
      $(identificador + idInicial).dateDropper("getDate", function (date) {
        fechaRetorno = date.Y + "-" + date.m + "-" + date.d;
      });
      break;
    case 2:
      $(identificador + idInicial).dateDropper("getDate", function (date) {
        fechaInicial = date.Y + "-" + date.m + "-" + date.d;
      });
      $(identificador + idFinal).dateDropper("getDate", function (date) {
        fechaFinal = date.Y + "-" + date.m + "-" + date.d;
      });
      fechaRetorno = fechaInicial + "," + fechaFinal;
      break;
    case 3:
      $(identificador + idInicial).dateDropper("getDate", function (date) {
        fechaRetorno = date.Y;
      });
      break;
    case 4:
      $(identificador + idInicial).dateDropper("getDate", function (date) {
        fechaRetorno = date.Y + "-" + date.m;
      });
      break;
    case 5:
      $(identificador + idInicial).dateDropper("getDate", function (date) {
        fechaRetorno = date.Y;
      });
      break;
  }

  return fechaRetorno;
}

function initDateDropper(
  ids,
  formato = 0,
  tema = 0,
  rangos = [],
  clase = 1,
  dateHoy = false
) {
  var temas = ["ryanair"];
  var identificador = "#";
  if (dateHoy) {
    dateHoy = moment().format("MM/DD/YYYY");
  }
  switch (clase) {
    case 2:
      identificador = ".";
      break;
    case 3:
      identificador = "";
      break;
    default:
      break;
  }
  for (let index = 0; index < ids.length; index++) {
    $(identificador + ids[index]).dateDropper({
      format: formatos[formato],
      lang: "es",
      theme: temas[tema],
      large: true,
      largeDefault: true,
      modal: true,
      minDate: dateHoy,
    });
  }
  if (rangos.length == 2) {
    //FECHA INICIAL
    $(identificador + ids[rangos[0]]).dateDropper("set", {
      maxDate: moment().format("MM/DD/YYYY"),
    });
    //FECHA FINALIZA_LOOP
    $(identificador + ids[rangos[1]]).dateDropper("set", {
      onChange: function (res) {
        $(identificador + ids[rangos[0]]).dateDropper(
          "getDate",
          function (date) {
            if (res.date.U < date.U) {
              $(identificador + ids[rangos[0]]).dateDropper("set", {
                maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
                defaultDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
              });
              $(identificador + ids[rangos[0]]).val(
                res.date.l +
                  ", " +
                  res.date.d +
                  " de " +
                  res.date.F +
                  " del " +
                  res.date.Y
              );
            } else {
              $(identificador + ids[rangos[0]]).dateDropper("set", {
                maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
                defaultDate: date.m + "/" + date.d + "/" + date.Y,
              });
            }
          }
        );
      },
    });
  }
}

function setDateDropper(id, fecha, formato = 0, clase = 1) {
  var identificador = "#";
  switch (clase) {
    case 2:
      identificador = ".";
      break;
    case 3:
      identificador = "";
      id = 'input[name="' + id + '"]';
      break;
    default:
      break;
  }
  $(identificador + id).dateDropper("set", {
    defaultDate: moment(fecha).format("MM/DD/YYYY"),
  });
  $(identificador + id).dateDropper("getDate", function (date) {
    switch (formato) {
      case 0:
        $(identificador + id).val(date.F + "/" + date.d + "/" + date.Y);
        break;
      case 1:
        $(identificador + id).val(
          date.l + ", " + date.d + " de " + date.F + " del " + date.Y
        );
        break;
      case 2:
        $(identificador + id).val(date.F + " del " + date.Y);
        break;
      case 3:
        $(identificador + id).val(date.Y);
        break;
      default:
        $(identificador + id).val(date.F + "/" + date.d + "/" + date.Y);
        break;
    }
  });
}
