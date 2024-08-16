function cargarSelect(
  modalID = null,
  multiple = false,
  idselect,
  peticion,
  placeholder,
  valores = [],
  todos = false
) {
  var dropdownParent =
    modalID == null
      ? $(document.body)
      : $("#" + modalID + ">.modal-dialog>.modal-content");
  $.ajax({
    data: { select: peticion, valores: JSON.stringify(valores) }, //necesario para enviar archivos
    dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
    url: urlBase + "php/controller/ControllerCargarSelect.php", //url a donde hacemos la peticion
    type: "POST",
    success: function (result) {
      var estado = result.status;
      switch (estado) {
        case "0":
          $.toast({
            heading: "Sin Datos",
            text: "No existen datos para el Select: " + idselect,
            showHideTransition: "slide",
            icon: "info",
            position: "top-right",
          });
          break;
        case "1":
          $("#" + idselect).html(result.html);
          $("#" + idselect).select2({
            language: "es",
            dropdownParent: dropdownParent,
            tags: false,
            placeholder: placeholder,
            allowClear: false,
            multiple: multiple,
          });
          if (todos) {
            $("#" + idselect).prepend('<option value="X">Todos</option>');
          }
          $("#" + idselect)
            .val("")
            .trigger("change");
          break;
        default:
          break;
      }
    },
    error: function () {},
  });
}
