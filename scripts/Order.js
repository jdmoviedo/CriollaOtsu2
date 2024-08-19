$("#dtOrder")
    .on("init.dt", function () {})
    .DataTable({
        data: "",
        columns: [
            {
                title: "ID PEDIDO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "RESTAURANTE",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "FECHA PEDIDO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "NAMES",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "TELEFONO",
                className: "text-center text-nowrap",
                responsivePriority: 2,
            },
            {
                title: "DIRECCION",
                className: "text-center text-nowrap",
                responsivePriority: 2,
            },
            {
                title: "EMAIL",
                className: "text-center text-nowrap",
                responsivePriority: 2,
            },
            {
                title: "TOTAL",
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
$("#dtOrder").on("draw.dt", function () {
    $(".loader").fadeOut("slow");
});

let urlControllerOrder = urlBase + "php/controller/ControllerOrder.php";

$(document).ready(function () {
    filtrarRegistros();
});

function filtrarRegistros() {
    $("#dtOrder").DataTable().clear();
    $.ajax({
        data: {
            peticion: "buscarPedidos",
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerOrder, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    $("#dtOrder").DataTable().rows.add(result.datos).draw();
                    break;
                case "0":
                    $("#dtOrder").DataTable().draw();
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
            $("#dtOrder").DataTable().responsive.recalc();
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

function detalleOrden(id) {
    $.ajax({
        data: {
            peticion: "detalleOrden",
            IdOrder: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerOrder, //url a donde hacemos la peticion
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
                    $("#OrderDetail").html(result.html);
                    $("#ModalOrder")
                        .find("h5.modal-title")
                        .html("Detalle Pedido ID : " + id);
                    $("#ModalOrder").modal("show");
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
    var text = "";
    switch (estado) {
        case 2:
            text = "En Proceso";
            break;
        case 3:
            text = "Enviado";
            break;
        case 4:
            text = "Entregado";
            break;
    }
    Swal.fire({
        title: "Esta seguro de cambiar el estado a " + text,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Cambiar!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: {
                    peticion: "cambiarEstado",
                    IdOrder: id,
                    IdStatusOrder: estado,
                }, //necesario para enviar archivos
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlControllerOrder, //url a donde hacemos la peticion
                type: "POST",
                beforeSend: function () {
                    $(".overlayCargue").fadeIn("slow");
                },
                complete: function () {
                    $(".overlayCargue").fadeOut("slow");
                },
                success: function (result) {
                    console.log(result);
                    var estado = result.status;
                    switch (estado) {
                        case "0":
                            // Error
                            Swal.fire({
                                icon: "error",
                                title: "<strong>Error!</strong>",
                                html: "<h5>Se ha presentado un error eliminando el ciclo, por favor informar al SysAdmin.</h5>",
                                showCloseButton: true,
                                showConfirmButton: false,
                                cancelButtonText: "Cerrar",
                                cancelButtonColor: "#dc3545",
                                showCancelButton: true,
                                backdrop: true,
                            });
                            break;
                        case "1":
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Estado Cambiado!</strong>",
                                html: "",
                                showCloseButton: true,
                                showConfirmButton: false,
                                cancelButtonText: "Cerrar",
                                cancelButtonColor: "#dc3545",
                                showCancelButton: true,
                                backdrop: true,
                            });
                            filtrarRegistros();
                            break;
                        default:
                            // Code
                            break;
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                },
            });
        }
    });
}

function logOrden(IdOrder) {
    $.ajax({
        data: {
            peticion: "logOrden",
            IdOrder: IdOrder,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerOrder, //url a donde hacemos la peticion
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
                        title: "<strong>Trazabilidad Orden</strong>",
                        html: result.html,
                        showCloseButton: false,
                        confirmButtonText: "Cerrar",
                        confirmButtonColor: "#64a19d",
                        backdrop: true
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
