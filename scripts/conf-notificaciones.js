
function showToast(head, text, tipo, posicion, tiempo) {
  // tipo => success, info, warning, error
  // posicion => derecha, centro, izquierda
  tipo = tipo.toLowerCase();
  posicion = posicion.toLowerCase();
  switch (tipo) {
    case "success":
      loaderBg = "#46c35f";
      break;

    case "info":
      loaderBg = "#57c7d4";
      break;

    case "warning":
      loaderBg = "#f2a654";
      break;

    case "error":
      loaderBg = "#f96868";
      break;
  }
  switch (posicion) {
    case "derecha":
      posicion = "right";
      break;

    case "centro":
      posicion = "center";
      break;

    case "izquierda":
      posicion = "left";
      break;
  }
  resetToastPosition();
  $.toast({
    heading: head,
    text: text,
    showHideTransition: "slide",
    icon: tipo,
    loaderBg: loaderBg,
    position: "top-" + posicion,
    hideAfter: tiempo,
  });
}

resetToastPosition = function () {
  $(".jq-toast-wrap").removeClass(
    "bottom-left bottom-right top-left top-right mid-center"
  ); // to remove previous position class
  $(".jq-toast-wrap").css({
    top: "",
    left: "",
    bottom: "",
    right: "",
  }); //to remove previous position style
};
