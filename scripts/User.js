$("#dtUsuarios")
    .on("init.dt", function () {})
    .DataTable({
        data: "",
        columns: [
            {
                title: "ID USUARIO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "USUARIO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "NOMBRES",
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
    });
$("#dtUsuarios").on("draw.dt", function () {
    $(".overlayCargue").fadeOut("slow");
});

let edit = false;

$(document).ready(function () {
    cargarSelect(
        "ModalAsignarSubmodulo",
        false,
        "selectHome",
        "cargarSubModulos",
        "Seleccione la Pagina de Inicio"
        // { modulo: 1 }
    );
    filtrarRegistros();
});

let urlControllerUser = urlBase + "php/controller/ControllerUser.php";

function filtrarRegistros() {
    $("#dtUsuarios").DataTable().clear();
    $.ajax({
        data: {
            peticion: "buscarUsuarios",
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerUser, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    $("#dtUsuarios").DataTable().rows.add(result.datos).draw();
                    break;
                case "0":
                    $("#dtUsuarios").DataTable().draw();
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
            $("#dtUsuarios").DataTable().responsive.recalc();
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
            formData.append("peticion", "editarUsuario");
            var contrasenias = true;
        } else {
            formData.append("peticion", "crearUsuario");
            var contrasenias = validarcontrasenias($("#password"), $("#password1"));
        }
        if (contrasenias) {
            $.ajax({
                cache: false, //necesario para enviar archivos
                contentType: false, //necesario para enviar archivos
                processData: false, //necesario para enviar archivos
                data: formData, //necesario para enviar archivos
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlControllerUser, //url a donde hacemos la peticion
                type: "POST",
                beforeSend: function () {
                    if (edit == true) {
                    } else {
                    }

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
                                    title: "<strong>Usuario Creado</strong>",
                                    html: "<h5>El usuario se ha registrado exitosamente</h5>",
                                    showCloseButton: false,
                                    confirmButtonText: "Aceptar",
                                    confirmButtonColor: "#64a19d",
                                    backdrop: true,
                                });
                            } else {
                                Swal.fire({
                                    icon: "success",
                                    title: "<strong>Usuario Editado</strong>",
                                    html: "<h5>El usuario se ha editado exitosamente</h5>",
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
                                text: "Ya existe un usuario con este Nombre de Usuario",
                                showHideTransition: "slide",
                                icon: "error",
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
}

function editarRegistro(id) {
    edit = true;
    datosRegistro(id);
}

function datosRegistro(id) {
    $.ajax({
        data: {
            peticion: "datosUsuario",
            IdUser: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerUser, //url a donde hacemos la peticion
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
                        $("#ModalRegistro").find("h5.modal-title").html("Ver Usuario");
                        $("#btnRegistro").hide();
                        $("#btnRegistro").text("Registrar");
                        $("#btnRegistro").attr("onclick", "");
                        vercampos("#frmRegistro", 2);
                    } else {
                        $("#ModalRegistro").find("h5.modal-title").html("Editar Usuario");
                        $("#btnRegistro").show();
                        $("#btnRegistro").text("Editar");
                        $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
                        $("#UserName").prop("disabled", true);
                    }
                    $("#passwords").hide();
                    $("#IdUser").val(result.IdUser);
                    $("#Names").val(result.Names);
                    $("#UserName").val(result.UserName);
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
            IdUser: id,
            estado: estado,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerUser, //url a donde hacemos la peticion
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
    $("#ModalRegistro").find("h5.modal-title").html("Crear Usuario");
    $("#btnRegistro").show();
    $("#btnRegistro").text("Registrar");
    $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
    $("#passwords").show();
    $("#ModalRegistro").modal("show");
}

function reset() {
    vercampos("#frmRegistro", 1);
    limpiarCampos("#frmRegistro");
    edit = false;
}

function showModalAsignarSubmodulo(id) {
    $.ajax({
        data: {
            peticion: "cargarAsignacion",
            IdUser: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerUser, //url a donde hacemos la peticion
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
                    $("#ModalAsignarSubmodulo").find("h5.modal-title").html("Asignar Submodulos");
                    $("#btnRegistroAsignarSubmodulo").show();
                    $("#btnRegistroAsignarSubmodulo").text("Asignar");
                    $("#btnRegistroAsignarSubmodulo").attr("onclick", "asignarSubmodulo('frmRegistroAsignarSubmodulo'," + id + ");");
                    $("#modulos").html(result.html);
                    $(".chkModulos").each(function (index) {
                        new Switchery(this, {
                            color: "#28B97B",
                            secondaryColor: "#F5365C",
                            speed: "0.6s",
                            size: "small",
                        });
                    });

                    $(".chkModulos").on("change", function () {
                        if (this.checked) {
                            $("#selectModulo" + this.value).prop("disabled", false);
                            cargarSelect("ModalAsignarSubmodulo", true, "selectModulo" + this.value, "cargarSubModulos", "Seleccione el/los Submodulo/s", { modulo: this.value }, true);
                        } else {
                            $("#selectModulo" + this.value).prop("disabled", true);
                            $("#selectModulo" + this.value)
                                .val("")
                                .trigger("change");
                        }
                    });
                    for (const modulo in result.asignados) {
                        var submodulos = result.asignados[modulo];
                        $("#modulo" + modulo).click();

                        cambiarvaloreselect(modulo, submodulos, 1);
                    }
                    $("#selectHome").val(result.Home).trigger("change");
                    $("#ModalAsignarSubmodulo").modal("show");
                    break;
                case "2":
                    Swal.fire({
                        title: "Sin Datos!",
                        text: "Hubo un problema en la sql al mostrar los modulos.",
                        icon: "info",
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cerrar!",
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

function asignarSubmodulo(form, id) {
    var respuestavalidacion = validarcampos("#" + form);
    if (respuestavalidacion) {
        var formData = new FormData(document.getElementById(form)); //necesario para enviar archivos
        formData.append("peticion", "asignarSubmodulo");
        formData.append("IdUser", id);
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //necesario para enviar archivos
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlControllerUser, //url a donde hacemos la peticion
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
                        $.toast({
                            heading: "Error!",
                            text: "Se ha presentado un error, por favor informar al area de sistemas.",
                            showHideTransition: "slide",
                            icon: "error",
                            position: "top-right",
                        });
                        break;
                    case "1":
                        Swal.fire({
                            icon: "success",
                            title: "<strong>Submodulo/s asignado/s</strong>",
                            html: "<h5>El/los submodulo/s ha/n sido asignado/s exitosamente</h5>",
                            showCloseButton: false,
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#64a19d",
                            backdrop: true,
                        });
                        resetAsignarSubmodulo();
                        $("#ModalAsignarSubmodulo").modal("hide");
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
}

function resetAsignarSubmodulo() {
    limpiarCampos("#frmRegistroAsignarSubmodulo");
}

function cambiarvaloreselect(modulo, submodulos, tipo) {
    setTimeout(() => {
        if (tipo == 1) {
            $("#selectModulo" + modulo)
                .val(submodulos)
                .trigger("change");
        } else if (tipo == 2) {
            $("#selectPermiso" + modulo)
                .val(submodulos)
                .trigger("change");
        }
    }, 1000);
}

function todos(id) {
    if ($("#" + id + " option[value='X']").prop("selected")) {
        $("#" + id + " > option").prop("selected", "selected"); // Select All Options
        $("#" + id + " option[value='X']").prop("selected", false);
        $("#" + id + "").trigger("change"); // Trigger change to select 2
    }
}
