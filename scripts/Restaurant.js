$("#dtRestaurant")
    .on("init.dt", function () {})
    .DataTable({
        data: "",
        columns: [
            {
                title: "ID RESTAURANTE",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "DESCRIPCION",
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
$("#dtRestaurant").on("draw.dt", function () {
    $(".loader").fadeOut("slow");
});

let edit = false;
let urlControllerRestaurant = urlBase + "php/controller/ControllerRestaurant.php";

$(document).ready(function () {
    filtrarRegistros();
});

function filtrarRegistros() {
    $("#dtRestaurant").DataTable().clear();
    $.ajax({
        data: {
            peticion: "buscarRestaurantes",
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerRestaurant, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    $("#dtRestaurant").DataTable().rows.add(result.datos).draw();
                    break;
                case "0":
                    $("#dtRestaurant").DataTable().draw();
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
            $("#dtRestaurant").DataTable().responsive.recalc();
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
            formData.append("peticion", "editarRestaurante");
        } else {
            formData.append("peticion", "crearRestaurante");
        }
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //necesario para enviar archivos
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlControllerRestaurant, //url a donde hacemos la peticion
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
                                title: "<strong>Restaurante Creado</strong>",
                                html: "<h5>El Restaurante se ha registrado exitosamente</h5>",
                                showCloseButton: false,
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#64a19d",
                                backdrop: true,
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Restaurante Editado</strong>",
                                html: "<h5>El Restaurante se ha editado exitosamente</h5>",
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
                            text: "Ya existe un Restaurante con este nombre",
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
            peticion: "datosRestaurante",
            IdRestaurant: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerRestaurant, //url a donde hacemos la peticion
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
                        $("#ModalRegistro").find("h5.modal-title").html("Ver Restaurante");
                        $("#btnRegistro").hide();
                        $("#btnRegistro").text("Registrar");
                        $("#btnRegistro").attr("onclick", "");
                        vercampos("#frmRegistro", 2);
                    } else {
                        $("#ModalRegistro").find("h5.modal-title").html("Editar Restaurante");
                        $("#btnRegistro").show();
                        $("#btnRegistro").text("Editar");
                        $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
                    }
                    $("#IdRestaurant").val(result.IdRestaurant);
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

function showModalRegistro() {
    $("#ModalRegistro").find("h5.modal-title").html("Crear Restaurante");
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
