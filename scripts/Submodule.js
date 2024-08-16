$("#dtSubmodulos")
    .on("init.dt", function () {})
    .DataTable({
        data: "",
        columns: [
            {
                title: "ID SUBMODULO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "MODULO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "SUBMODULO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "DESCRIPCION",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "ESTADO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
        ],
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        responsive: true,
        ordering: false,
        language: {
            url: urlBase + "scripts/plugins/DataTable/language/Spanish.json",
        },
        // createdRow: function (row, data, index) {
        //   if (index % 2) {
        //     $(row).addClass("bg-blue");
        //   }
        // },
    });
$("#dtSubmodulos").on("draw.dt", function () {
    $(".overlayCargue").fadeOut("slow");
});

let edit = false;
let urlControllerSubmodule = urlBase + "php/controller/ControllerSubmodule.php";

$(document).ready(function () {
    cargarSelect("ModalRegistro", false, "selectModulo", "cargarModulos", "Seleccione el Modulo");
    filtrarRegistros();
});

function filtrarRegistros() {
    $("#dtSubmodulos").DataTable().clear();
    $.ajax({
        data: {
            peticion: "buscarSubmodulos",
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerSubmodule, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    $("#dtSubmodulos").DataTable().rows.add(result.datos).draw();
                    break;
                case "0":
                    $("#dtSubmodulos").DataTable().draw();
                    $.toast({
                        heading: "Información!",
                        text: "Sin registros",
                        showHideTransition: "slide",
                        icon: "info",
                        position: "top-right",
                    });
                    break;
                default:
            }
        },
        complete: function () {
            $(".overlayCargue").fadeOut("slow");
            $("#dtSubmodulos").DataTable().responsive.recalc();
        },
        error: function (xhr) {
            console.log(xhr);
            Swal.fire({
                icon: "error",
                title: "<strong>Error!</strong>",
                html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                showCloseButton: true,
                showConfirmButton: false,
                cancelButtonText: "Cerrar",
                cancelButtonColor: "#dc3545",
                showCancelButton: true,
                backdrop: true,
            });
        },
    });
}

function registrar(form) {
    var respuestavalidacion = validarcampos("#" + form);
    if (respuestavalidacion) {
        var formData = new FormData(document.getElementById(form)); //necesario para enviar archivos
        if (edit == true) {
            formData.append("peticion", "editarSubmodulo");
        } else {
            formData.append("peticion", "crearSubmodulo");
        }
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //necesario para enviar archivos
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlControllerSubmodule, //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
                $(".overlayCargue").fadeIn("slow");
            },
            complete: function () {
                $(".overlayCargue").fadeOut("slow");
            },
            success: function (result) {
                var estado = result.status;
                switch (estado) {
                    case "0":
                        Swal.fire({
                            icon: "error",
                            title: "<strong>Error!</strong>",
                            html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                            showCloseButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "Cerrar",
                            cancelButtonColor: "#dc3545",
                            showCancelButton: true,
                            backdrop: true,
                        });
                        break;
                    case "1":
                        if (edit == false) {
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Submodulo Creado</strong>",
                                html: "<h5>El submodulo se ha registrado exitosamente</h5>",
                                showCloseButton: false,
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#64a19d",
                                backdrop: true,
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Submodulo Editado</strong>",
                                html: "<h5>El submodulo se ha editado exitosamente</h5>",
                                showCloseButton: false,
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#64a19d",
                                backdrop: true,
                            });
                        }
                        reset();
                        $("#ModalRegistro").modal("hide");
                        filtrarRegistros();
                        break;
                    case "2":
                        $.toast({
                            heading: "Error!",
                            text: "Ya existe un Submodulo con este nombre",
                            showHideTransition: "slide",
                            icon: "info",
                            position: "top-right",
                        });
                        break;
                    default:
                        // Code
                        break;
                }
            },
            error: function (xhr) {
                console.log(xhr);
                Swal.fire({
                    icon: "error",
                    title: "<strong>Error!</strong>",
                    html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                    showCloseButton: true,
                    showConfirmButton: false,
                    cancelButtonText: "Cerrar",
                    cancelButtonColor: "#dc3545",
                    showCancelButton: true,
                    backdrop: true,
                });
            },
        });
    }
}

function editarRegistro(id) {
    edit = true;
    datosRegistro(id);
}

function datosRegistro(id) {
    $.ajax({
        data: {
            peticion: "datosSubmodulo",
            IdSubmodule: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerSubmodule, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "0":
                    Swal.fire({
                        title: "Error!",
                        text: "Se ha presentado un error, comuniquese con el area de sistemas.",
                        icon: "error",
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cerrar!",
                    });
                    break;
                case "1":
                    if (edit == false) {
                        $("#ModalRegistro").find("h5.modal-title").html("Ver Submodulo");
                        $("#btnRegistro").hide();
                        $("#btnRegistro").text("Registrar");
                        $("#btnRegistro").attr("onclick", "");
                        vercampos("#frmRegistro", 2);
                    } else {
                        $("#ModalRegistro").find("h5.modal-title").html("Editar Submodulo");
                        $("#btnRegistro").show();
                        $("#btnRegistro").text("Editar");
                        $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
                    }
                    $("#IdSubmodule").val(result.IdSubmodule);
                    $("#selectModulo").val(result.IdModule).trigger("change");
                    $("#Submodule").val(result.Submodule);
                    $("#Description").val(result.Description);
                    $("#ModalRegistro").modal("show");
                    break;
                case "2":
                    $.toast({
                        heading: "Información!",
                        text: "Sin datos",
                        showHideTransition: "slide",
                        icon: "info",
                        position: "top-right",
                    });
                    break;
                default:
                    break;
            }
        },
        complete: function () {
            $(".overlayCargue").fadeOut("slow");
        },
        error: function (xhr) {
            console.log(xhr);
            Swal.fire({
                icon: "error",
                title: "<strong>Error!</strong>",
                html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                showCloseButton: true,
                showConfirmButton: false,
                cancelButtonText: "Cerrar",
                cancelButtonColor: "#dc3545",
                showCancelButton: true,
                backdrop: true,
            });
        },
    });
}

function cambiarEstado(id, estado) {
    $.ajax({
        data: {
            peticion: "cambiarEstado",
            IdSubmodule: id,
            IdStatus: estado,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerSubmodule, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "0":
                    Swal.fire({
                        title: "Error!",
                        text: "Se ha presentado un error, comuniquese con el area de sistemas.",
                        icon: "error",
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cerrar!",
                    });
                    break;
                case "1":
                    Swal.fire({
                        title: "Cambio de Estado Satisfactorio",
                        text: "",
                        icon: "success",
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cerrar!",
                    });
                    filtrarRegistros();
                    break;
                default:
                    break;
            }
        },
        complete: function () {
            $(".overlayCargue").fadeOut("slow");
        },
        error: function (xhr) {
            console.log(xhr);
            Swal.fire({
                icon: "error",
                title: "<strong>Error!</strong>",
                html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                showCloseButton: true,
                showConfirmButton: false,
                cancelButtonText: "Cerrar",
                cancelButtonColor: "#dc3545",
                showCancelButton: true,
                backdrop: true,
            });
        },
    });
}

function showModalRegistro() {
    $("#ModalRegistro").find("h5.modal-title").html("Crear Submodulo");
    $("#btnRegistro").show();
    $("#btnRegistro").text("Registrar");
    $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
    $("#ModalRegistro").modal("show");
}

function reset() {
    vercampos("#frmRegistro", 1);
    limpiarCampos("#frmRegistro");
    edit = false;
}
