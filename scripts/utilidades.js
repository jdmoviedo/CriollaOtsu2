function habilitarcampos(contenedor) {
  $(contenedor)
    .find(".form-control")
    .filter(":visible")
    .each(function () {
      $(this).attr("disabled", false);
    });
}

function deshabilitarcampos(contenedor) {
  $(contenedor)
    .find(".form-control")
    .filter(":visible")
    .each(function () {
      $(this).attr("disabled", true);
    });
}

function aleatorio(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}
function ponerPuntosMil(numero) {
  var numeroStr = numero.toString();
  //cuento los digitos del numero
  var digitos = numeroStr.length;
  //establezco cuantos digitos sobran (resto)
  var digitosSobrantes = digitos % 3;
  var cantidadPuntos = 0;
  if (digitos < 4) {
    cantidadPuntos = 0;
  } else {
    if (digitosSobrantes != 0) {
      cantidadPuntos = (digitos - digitosSobrantes) / 3;
    } else {
      cantidadPuntos = (digitos - digitosSobrantes) / 3 - 1;
    }
  }
  var inicioCadena = 0;
  var posicionPunto = digitosSobrantes == 0 ? 3 : digitosSobrantes;
  var numeroFormateado = "";
  for (var i = 0; i < cantidadPuntos; i++) {
    numeroFormateado += numeroStr.substring(inicioCadena, posicionPunto) + ".";
    inicioCadena = posicionPunto;
    posicionPunto = posicionPunto + 3;
  }
  numeroFormateado += numeroStr.substring(inicioCadena, posicionPunto);
  return numeroFormateado;
}
//verificar si es movil
var esMovil = {
  Android: function () {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function () {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function () {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function () {
    return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function () {
    return navigator.userAgent.match(/IEMobile/i);
  },
  any: function () {
    return (
      esMovil.Android() ||
      esMovil.BlackBerry() ||
      esMovil.iOS() ||
      esMovil.Opera() ||
      esMovil.Windows()
    );
  },
};

//desplazarse al id de un elemento
function desplazarseAId(id) {
  $("html,body").animate(
    {
      scrollTop: $("#" + id).offset().top - 50,
    },
    1200
  );
}
//desplazarse a elemento
function desplazarseAElemento(elemento, tiempo) {
  $("html,body").animate(
    {
      scrollTop: $(elemento).offset().top - 30,
    },
    tiempo
  );
}

//funcion para agregar o remover una clase a un elemento
//id del elemento
//clase a alternar
//agregar-true / eliminar-false
function agregarOEliminarClase(id, clase, accion) {
  $("#" + id).toggleClass(clase, accion);
}
//funcion para recibir parametro get javascript basado en la url
function $_GET(param) {
  /* Obtener la url completa */
  url = document.URL;
  /* Buscar a partir del signo de interrogación ? */
  url = String(url.match(/\?+.+/));
  /* limpiar la cadena quitándole el signo ? */
  url = url.replace("?", "");
  /* Crear un array con parametro=valor */
  url = url.split("&");

  /*
    Recorrer el array url
    obtener el valor y dividirlo en dos partes a través del signo =
    0 = parametro
    1 = valor
    Si el parámetro existe devolver su valor
    */
  x = 0;
  while (x < url.length) {
    p = url[x].split("=");
    if (p[0] == param) {
      return decodeURIComponent(p[1]);
    }
    x++;
  }
}

function buscarSelect(select, valorBuscado) {
  var respuesta = false;
  $(select)
    .children("option")
    .each(function (index, el) {
      if (this.value == valorBuscado) {
        respuesta = true;
      }
    });
  return respuesta;
}

function ocultarAlert(botonCerrar) {
  $(botonCerrar).parent().fadeOut("slow");
}

function obtenerPosicion() {
  var scrollY =
    self.pageYOffset ||
    document.body.scrollTop + document.documentElement.scrollTop;
  return scrollY;
  //console.log("Posicion Top: "+scrollY);
}

function validacion(elemento, expresion) {
  var elem = elemento.val();
  // solo numeros
  var numeros = /^[0-9]+$/;
  //sólo letras, pero esto no incluye los acentos, así que si introduces á no es correcto.
  var letras = /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]+$/;
  //para caracteres latinos(acentos), espacios y guiones bajos. el espacio se indica con \s.
  var alfanumerico = /^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_-\s]+$/;
  //para emails, válidos pueden ser: miemail@gmail.com, mi.email@gmail.es, ...
  var correo = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
  //Para urls
  var urls = /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,6})?([\.\-\w\/_]+)$/i;
  // para contraseñas que contengan numeros y letras
  var password = /^([a-z]+[0-9]+)|([0-9]+[a-z]+)/i;
  // para alfanumerico y caracteres especiales, tipo mensajes
  var patronalfanumerico = /^[ 0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ.¡!¿?\s]+$/;

  switch (expresion) {
    case "numeros":
      return elem.match(numeros);
      break;
    case "letras":
      return elem.match(letras);
      break;
    case "alfanumerico":
      return elem.match(alfanumerico);
      break;
    case "correo":
      return elem.match(correo);
      break;
    case "url":
      return elem.match(urls);
      break;
    case "password":
      return elem.match(password);
      break;
    case "patronalfanumerico":
      return elem.match(patronalfanumerico);
      break;
    default:
      break;
  }
}

// campo valido
// campo de solo letras
// $('#elemento').validCampo('abcdefghijklmnñopqrstuvwxyzáéiou');
// campo de solo numero
// $('#elemento').validCampo('0123456789');
(function (a) {
  a.fn.validCampo = function (b) {
    a(this).on({
      keypress: function (a) {
        var c = a.which,
          d = a.keyCode,
          e = String.fromCharCode(c).toLowerCase(),
          f = b;
        ((-1 != f.indexOf(e) ||
          9 == d ||
          (37 != c && 37 == d) ||
          (39 == d && 39 != c) ||
          8 == d ||
          (46 == d && 46 != c)) &&
          161 != c) ||
          a.preventDefault();
      },
    });
  };
})(jQuery);

function in_array(valor, arry) {
  var boolArry = false;
  for (var i = 0; i < arry.length; i++) {
    if (valor == arry[i]) {
      boolArry = true;
    }
  }
  return boolArry;
}

function pulsaenter(event, id) {
  var esIE = document.all;
  var esNS = document.layers;
  tecla = esIE ? event.keyCode : event.which;
  if (tecla == 13) {
    switch (id) {
      case "usuarioLogin":
        Login("frmLogin");
        break;

      case "contraseniaLogin":
        Login("frmLogin");
        break;

      case "filtroRegistro":
        show_registros();
        break;

      default:
        // Code
        break;
    }
  }
}

function setIframeLocation(element, value) {
  if (element.contentWindow !== null) {
    element.contentWindow.location.replace(value);
  } else {
    setTimeout(setIframeLocation.bind(this, element, value), 100);
  }
  $("#ModalPdfGenerado").modal("show");
}

function resta(idInputUno, idInputDos, idInputResultado) {
  var valor1 = $("#" + idInputUno)
    .val()
    .replace(".", "");
  var valor2 = $("#" + idInputDos)
    .val()
    .replace(".", "");
  // console.log(valor1);|
  var valorTotal = 0;
  valorTotal = valor1 - valor2;
  $("#" + idInputResultado).val(ponerPuntosMil(valorTotal));
}

function eliminarEspacios(elemento) {
  var input = $(elemento);
  var valor = input.val().replace(" ", "");
  input.val(valor);
}

String.prototype.reverse = function () {
  var str = this,
    newString = new String();
  for (n = str.length; n >= 0; n--) {
    newString += str.charAt(n);
  }
  return newString;
};

// Validacion de campo vacio
function isEmpty(valor) {
  return !$.trim(valor);
}

$(document).ready(function () {
  var last = window.location.pathname.lastIndexOf("/") + 1;
  var pagina = window.location.pathname.substring(last).replace(".php", "");
  // console.log(pagina);
  $("a[href='" + pagina + "']").addClass("active");

  $("a[href='" + pagina + "']")
    .parent(".submenu-content")
    .parent(".nav-item")
    .children("a")
    .addClass("active");

  $("a[href='" + pagina + "']")
    .parent(".submenu-content")
    .parent(".nav-item")
    .addClass("open");
  $(".select2").select2({
    language: "es",
    allowClear: false,
    multiple: false,
  });
  $(".select2-multiple").select2({
    language: "es",
    allowClear: false,
    multiple: true,
  });
  $("table.dataTable").on("search.dt", function () {
    var value = $("#" + this.id + "_filter input").val();
    if ($(this).DataTable().column(0).data().length > 0 && value != "") {
      $("#" + this.id)
        .DataTable()
        .responsive.recalc();
    }
  });
  $(".modal").on(
    "hidden.bs.modal",
    () => $(".modal:visible").length && $(document.body).addClass("modal-open")
  );
  $(".modal").on("show.bs.modal", function (e) {
    const zIndex = 1040 + 10 * $(".modal:visible").length;
    $(this).css("z-index", zIndex);
    setTimeout(() =>
      $(".modal-backdrop")
        .not(".modal-stack")
        .css("z-index", zIndex - 1)
        .addClass("modal-stack")
    );
  });
});
