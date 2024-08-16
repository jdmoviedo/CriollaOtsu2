$("#dtProduct")
    .on("init.dt", function () {})
    .DataTable({
        data: "",
        columns: [
            {
                title: "ID PRODUCTO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "RESTAURANTE",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "TIPO PRODUCTO",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "DESCRIPCION",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "DESCRIPCION LARGA",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "VALOR",
                className: "text-center text-nowrap",
                responsivePriority: 1,
            },
            {
                title: "IMAGEN",
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
        layout: {
            topStart: {
                pageLength: {
                    menu: [10, 25, 50, 100, -1],
                },
            },
        },
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
$("#dtProduct").on("draw.dt", function () {
    $(".overlayCargue").fadeOut("slow");
});

let edit = false;
let urlControllerProduct = urlBase + "php/controller/ControllerProduct.php";

$(document).ready(function () {
    cargarSelect("ModalRegistro", false, "selectRestaurant", "cargarRestaurantes", "Seleccione el Restaurante");
    cargarSelect("ModalRegistro", false, "selectTypeProduct", "cargarTipoProductos", "Seleccione el Tipo de Producto");
    filtrarRegistros();
});

function filtrarRegistros() {
    $("#dtProduct").DataTable().clear();
    $.ajax({
        data: {
            peticion: "buscarProductos",
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerProduct, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    $("#dtProduct").DataTable().rows.add(result.datos).draw();
                    break;
                case "0":
                    $("#dtProduct").DataTable().draw();
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
            $("#dtProduct").DataTable().responsive.recalc();
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
            formData.append("peticion", "editarProducto");
        } else {
            formData.append("peticion", "crearProducto");
        }
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //necesario para enviar archivos
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlControllerProduct, //url a donde hacemos la peticion
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
                                title: "<strong>Producto Creado</strong>",
                                html: "<h5>El Producto se ha registrado exitosamente</h5>",
                                showCloseButton: false,
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#64a19d",
                                backdrop: true,
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Producto Editado</strong>",
                                html: "<h5>El Producto se ha editado exitosamente</h5>",
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
                            text: "Ya existe un Producto con este nombre",
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
            peticion: "datosProducto",
            IdProduct: id,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerProduct, //url a donde hacemos la peticion
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
                        $("#ModalRegistro").find("h5.modal-title").html("Ver Producto");
                        $("#btnRegistro").hide();
                        $("#btnRegistro").text("Registrar");
                        $("#btnRegistro").attr("onclick", "");
                        vercampos("#frmRegistro", 2);
                    } else {
                        $("#ModalRegistro").find("h5.modal-title").html("Editar Producto");
                        $("#btnRegistro").show();
                        $("#btnRegistro").text("Editar");
                        $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
                    }
                    $("#IdProduct").val(result.IdProduct);
                    $("#selectRestaurant").val(result.IdRestaurant).trigger("change");
                    $("#selectTypeProduct").val(result.IdTypeProduct).trigger("change");
                    $("#Value").val(result.Value);
                    $("#Description").val(result.Description);
                    $("#LongDescription").val(result.LongDescription);
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
            IdProduct: id,
            IdStatusProduct: estado,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerProduct, //url a donde hacemos la peticion
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
    $("#ModalRegistro").find("h5.modal-title").html("Crear Producto");
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

function verImagen(url) {
    Swal.fire({
        position: "top",
        html: '<img src="' + urlBase + url + '">',
        width: 1000,
    });
}

function subirImagen(IdProduct, Description) {
    Swal.fire({
        position: "top",
        icon: "info",
        title: "<strong>Subir Imagen</strong>",
        html: `<h5>Esta seguro que desea subir la imagen de <strong>${Description}</strong>?</h5>
        <a class="tooltips mt-4">
            <label class="negrita" for="fileUpload">Subir Imagen</label>
            <input type="file" class="form-control maxlength-input" minlength="0" maxlength="200" value="" title="Ingrese fileUpload" id="fileUpload" name="fileUpload">
            <span class="spanValidacion hidden"></span>
        </a>`,
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Si, subir",
        confirmButtonColor: "#26b99a",
        cancelButtonText: "No, cancelar",
        cancelButtonColor: "#dc3545",
        backdrop: true,
        width: 600,
        allowEscapeKey: false,
        showLoaderOnConfirm: true,
        didOpen: () => {
            $("input.maxlength-input").maxlength({
                alwaysShow: true,
                warningClass: "hoverMaxlength hoverMaxlength-success",
                limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
                placement: "top",
                validate: true,
            });
        },
        preConfirm: (res) => {
            let fileInput = document.getElementById("fileUpload").files[0];
            if (fileInput != undefined) {
                var typeFile = fileInput.type;
                var sizeFile = fileInput.size;
                if (typeFile == "image/jpeg" || typeFile == "image/png") {
                    if (sizeFile <= 20971520) {
                        return guardarImagen(IdProduct, fileInput)
                            .then((response) => {
                                if (response.status == 1) {
                                    filtrarRegistros();
                                    Swal.fire({
                                        icon: "success",
                                        title: "<strong>Guardado Exitoso</strong>",
                                        html: "",
                                        showCloseButton: true,
                                        confirmButtonText: "Entendido",
                                        backdrop: true,
                                    });
                                } else {
                                    Swal.showValidationMessage(`Request failed: ${response.status}`);
                                }
                            })
                            .catch((error) => {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            });
                    } else {
                        Swal.showValidationMessage("El peso del archivo supera las 20Mb");
                    }
                } else {
                    Swal.showValidationMessage("El tipo de archivo no es valido");
                }
            } else {
                Swal.showValidationMessage("Debe cargar un archivo");
            }
        },
        allowOutsideClick: () => !Swal.isLoading(),
    });
}

function guardarImagen(IdProduct, image) {
    return new Promise((resolve, reject) => {
        var formData = new FormData();
        formData.append("peticion", "subirImagen");
        formData.append("IdProduct", IdProduct);
        formData.append("image", image);
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //datos a enviar a la url
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlControllerProduct, //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
                $(".overlayCargue").fadeIn("slow");
            },
            success: function (result) {
                var estado = result.status;
                switch (estado) {
                    case "0":
                        reject("Error");
                        break;

                    case "1":
                        resolve(result);
                        break;

                    case "2":
                        reject("Tamaño del archivo no permitido");
                        break;

                    case "3":
                        reject("Tipo de archivo nopermitido");
                        break;

                    case "4":
                        reject("Error en el archivo");
                        break;

                    case "5":
                        reject("Registro invalido, comuniquese con sistemas");
                        break;

                    case "6":
                        reject("Error crendo las carpetas para guardar");
                        break;
                }
            },
            complete: function () {
                $(".overlayCargue").fadeOut("slow");
            },
            error: function (xhr) {
                console.error(xhr);
            },
        });
    });
}

function ProductHasIngredient(IdProduct, Description) {
    $("#IdProductHasIngredient").val(IdProduct);
    $("#divProductHasIngredient").html("");
    $("#ModalRegistroProductHasIngredient")
        .find("h5.modal-title")
        .html("Asociar Ingredientes - " + Description);
    $("#btnRegistroProductHasIngredient").show();
    $("#btnRegistroProductHasIngredient").text("Asociar");
    $("#btnRegistroProductHasIngredient").attr("onclick", "registrarProductHasIngredient('frmRegistroProductHasIngredient');");
    $("#ModalRegistroProductHasIngredient").modal("show");

    $.ajax({
        data: {
            peticion: "ProductHasIngredient",
            IdProduct: IdProduct,
        }, //datos a enviar a la url
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlControllerProduct, //url a donde hacemos la peticion
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
                    $("#divProductHasIngredient").html(result.html);
                    break;
                case "2":
                    $("#divProductHasIngredient").html(result.html);
                    $.toast({
                        heading: "Información!",
                        text: "Sin Ingredientes Asociados",
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
            $(".requerido").on("change click mouseleave keypress", function (e) {
                if ($(this).is("select") == true) {
                    $(this).siblings("span").removeClass("required");
                    $(this).parents(".tooltips").find(".spanValidacion").fadeOut();
                } else {
                    $(this).removeClass("required");
                    $(this).parents(".tooltips").find(".spanValidacion").fadeOut();
                }
            });

            $(".spanValidacion").on("change click mouseleave keypress", function (e) {
                $(this).fadeOut();
            });

            $(".numero").inputmask("numero");
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

function deleteProductHasIngredient(element) {
    $(element).closest(".divIngredient").remove();
}

function AddIngredient() {
    var cantidad = $(".divIngredient").length + 1;

    let html =
        `
    <div class="divIngredient align-items-center d-flex">
        <div class="col-6 form-group">
            <a class="tooltips">
                <select class="form-control requerido" id="selectIngredient` +
        cantidad +
        `" title="Ingrediente" style="width:100%">
                </select>
                <span class="spanValidacion hidden"></span>
            </a>
        </div>
        <div class="col-5 form-group">
            <a class="tooltips">
             <input type="text" class="form-control requerido maxlength-input numero" title="Valor" placeholder="Valor" minlength="1" maxlength="20">
                <span class="spanValidacion hidden"></span>
            </a>
        </div>
        <div class="col-1 form-group text-center">
            <i class="fa-solid fa-trash cursor-pointer" onclick="deleteProductHasIngredient(this)"></i>
        </div>
    </div>`;

    $("#divProductHasIngredient").append(html);

    cargarSelect("ModalRegistroProductHasIngredient", false, "selectIngredient" + cantidad, "cargarIngredientes", "Seleccione el Ingrediente");

    $(".requerido").on("change click mouseleave keypress", function (e) {
        if ($(this).is("select") == true) {
            $(this).siblings("span").removeClass("required");
            $(this).parents(".tooltips").find(".spanValidacion").fadeOut();
        } else {
            $(this).removeClass("required");
            $(this).parents(".tooltips").find(".spanValidacion").fadeOut();
        }
    });

    $(".spanValidacion").on("change click mouseleave keypress", function (e) {
        $(this).fadeOut();
    });

    $(".numero").inputmask("numero");
}

function registrarProductHasIngredient(form) {
    var respuestavalidacion = validarcampos("#" + form);
    if (respuestavalidacion) {
        let datos = [];
        let unique = [];
        let bUnique = true;

        $(".divIngredient").each(function () {
            let select = $(this).find(".col-6").find("select").length;
            let IdIngredient = 0;

            if (select > 0) {
                IdIngredient = $(this).find(".col-6").find("select").val();
            } else {
                IdIngredient = $(this).find(".col-6").find("input").val();
            }
            let Value = $(this).find(".col-5").find("input").val();

            let obj = { IdIngredient: IdIngredient, Value: Value };

            datos.push(obj);

            if (unique.includes(IdIngredient)) {
                bUnique = false;
            }
            unique.push(IdIngredient);
        });

        if (bUnique) {
            $.ajax({
                data: {
                    peticion: "registrarProductHasIngredient",
                    IdProduct: $("#IdProductHasIngredient").val(),
                    Datos: datos,
                }, //datos a enviar a la url
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlControllerProduct, //url a donde hacemos la peticion
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
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Ingredienes Asociados</strong>",
                                html: "<h5>Los Ingredientes fueron asociados con exito</h5>",
                                showCloseButton: false,
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#64a19d",
                                backdrop: true,
                            });
                            $("#ModalRegistroProductHasIngredient").modal("hide");
                            filtrarRegistros();
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
        } else {
            $.toast({
                heading: "Información!",
                text: "Ingredientes Duplicados",
                showHideTransition: "slide",
                icon: "info",
                position: "top-right",
            });
        }
    }
}
