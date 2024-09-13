$(document).ready(function () {
    Swal.close();
});

let urlController = urlBase + "php/controller/ControllerLogin.php";

function Login(form) {
    var respuestavalidacion = validarcampos("#" + form);
    if (respuestavalidacion) {
        $("#btnLoginIngresar").prop("disabled", true);
        var formData = new FormData(document.getElementById(form)); //necesario para enviar archivos
        formData.append("peticion", "login"); //necesario para enviar archivos   */
        $.ajax({
            cache: false, //necesario para enviar archivos
            contentType: false, //necesario para enviar archivos
            processData: false, //necesario para enviar archivos
            data: formData, //necesario para enviar archivos
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlController, //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
                $("#overlayText").text("Iniciando Sesion...");
                $(".overlayCargue").fadeIn("slow");
            },
            complete: function () {
                $(".overlayCargue").fadeOut("slow");
            },
            success: function (result) {
                $("#btnLoginIngresar").prop("disabled", false);
                var estado = result.status;
                switch (estado) {
                    case "0":
                        Swal.fire({
                            icon: "error",
                            title: "<strong>Error!</strong>",
                            html: "<h5>Se ha presentado un error validando el inicio de sesión, por favor informar al SysAdmin.</h5>",
                            showCloseButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "Cerrar",
                            cancelButtonColor: "#dc3545",
                            showCancelButton: true,
                            backdrop: true,
                        });
                        break;

                    case "1":
                        if (result.url != "") {
                            window.location = result.url;
                        } else {
                            window.location.replace(urlBase + "Inicio");
                        }
                        break;

                    case "2":
                        var mensajeAlerta = "";
                        mensajeAlerta = "Usuario incorrecto";
                        $("#usuarioLogin").addClass("required");
                        $("#usuarioLogin").parents(".tooltips").find(".spanValidacion").text(mensajeAlerta);
                        $("#usuarioLogin").parents(".tooltips").find(".spanValidacion").fadeIn();
                        $("html,body").animate({ scrollTop: $("#usuarioLogin").position }, 200, "swing", function () {
                            $("#usuarioLogin").focus();
                        });

                        break;
                    case "3":
                        Swal.fire({
                            icon: "info",
                            title: "<strong>Usuario Inactivo!</strong>",
                            html: "<h5>El usuario se encuentra inactivo, comuniquese con SysAdmin.</h5>",
                            showCloseButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "Cerrar",
                            cancelButtonColor: "#dc3545",
                            showCancelButton: true,
                            backdrop: true,
                        });
                        break;
                    case "4":
                        Swal.fire({
                            icon: "info",
                            title: "<strong>Contraseña errada</strong>",
                            showCloseButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "Cerrar",
                            cancelButtonColor: "#dc3545",
                            showCancelButton: true,
                            backdrop: true,
                        });
                        break;

                    default:
                        break;
                }
            },
            error: function (xhr) {
                $("#btnLoginIngresar").prop("disabled", false);
            },
        });
    }
}

function cerrarSesion() {
    $.ajax({
        data: { peticion: "logout" }, //necesario para enviar archivos
        dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
        url: urlController, //url a donde hacemos la peticion
        type: "POST",
        beforeSend: function () {
            $("#overlayText").text("Cerrando Sesión...");
            $(".overlayCargue").fadeOut("slow");
        },
        complete: function () {
            $(".overlayCargue").fadeIn("slow");
        },
        success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                    window.location.replace(urlBase + "Login");
                    break;
            }
        },
        error: function (xhr) {
            console.log(xhr);
        },
    });
}

//funcion para preguntar si desea seguir conectado
// var seguirConectado = function () {
//   var tiempoRestante = 30;
//   var contadorRegresivoSeguirConectado = setInterval(function () {
//     tiempoRestante -= 1;
//     if (tiempoRestante == 0) {
//       clearInterval(contadorRegresivoSeguirConectado);
//     }
//     $("#cuentaAtrasDesconexion").html(tiempoRestante);
//   }, 1000);
//   $("#cuentaAtrasDesconexion").html(30);
//   Swal.fire({
//     icon: "info",
//     position: "top",
//     title: "<strong>Aviso de Sesion</strong>",
//     html:
//       "<h5>Su sesion esta a punto de finalizar</h5>" +
//       "<p>Han transcurrido cerca de 15 minutos desde tu última interacción con la plataforma.</p>" +
//       '<p>Para continuar logueado da clic en "Seguir conectado", de lo contrario tu sesión expirará.</p>' +
//       '<h1 class="text-center"><span class="cuenta-inactividad" id="cuentaAtrasDesconexion">30</span></h1>',
//     showCloseButton: false,
//     confirmButtonText: "Seguir conectado",
//     confirmButtonColor: "#3e866f",
//     backdrop: true,
//     allowOutsideClick: false,
//     allowEscapeKey: false,
//     allowEnterKey: false,
//     timer: tiempoEspera,
//     timerProgressBar: true,
//   }).then((result) => {
//     if (result.isConfirmed) {
//       // Actualizamos la sesion
//       actualizarUltimaConexionOnline();
//     }
//   });
// };

// 15000
// 300000 milisegundos - 5 minutos
// 600000 milisegundos - 10 minutos
// 900000 milisegundos - 15 minutos
// 30000 tiempo de Espera
//variables globales para los timer de desconexion
// var contadorCerrarSesion;
// var contadorSeguirConectado;
// var tiempoMaximo = 900000;
// var tiempoEspera = 30000;
// var tiempoMaximoCierreSesion = tiempoMaximo + tiempoEspera;

// function actualizarUltimaConexion() {
//   //reinicio los timer
//   clearTimeout(contadorCerrarSesion);
//   clearTimeout(contadorSeguirConectado);
//   contadorCerrarSesion = setTimeout(function () {
//     cerrarSesion();
//   }, tiempoMaximoCierreSesion);
//   contadorSeguirConectado = setTimeout(seguirConectado, tiempoMaximo);
// }

function actualizarUltimaConexionOnline() {
    // clearTimeout(contadorCerrarSesion);
    // actualizarUltimaConexion();
    // $.ajax({
    //     data: {'peticion':'continuarConectado'},//datos a enviar a la url
    //     dataType: 'json',//Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
    //     url:  urlGlobal+'php/controller/controller_login.php',//url a donde hacemos la peticion
    //     type:  'POST',
    //     beforeSend: function (){
    //     },
    //     success:  function (result){
    //         var estado = result.status;
    //         switch(estado) {
    //             case '0':
    //             	window.location.replace(urlGlobal + "login.php");
    //             break;
    //             case '1':
    //             	$("#modalSeguirConectado").modal('hide');
    //             break;
    //             default:
    //         }
    //     },
    //     complete: function(){
    //     },
    //     error: function(){
    //     }
    // });
}

function cambiarContrasenia() {
    let htmlInputs = `<a class="tooltips"><label class="negrita" for="passwordOne">Digite la contraseña nueva</label>
  <input type="password" class="form-control requerido maxlength-input" minlength="3" maxlength="100" title="Digite la contraseña nueva" id="passwordOne" name="passwordOne"><span class="spanValidacion hidden"></span></a>
  <a class="tooltips" style="margin-top: 14px;"><label class="negrita" for="passwordTwo">Repita la contraseña</label>
  <input type="password" class="form-control maxlength-input" minlength="0" maxlength="100" title="Repita la contraseña" id="passwordTwo" name="passwordTwo"><span class="spanValidacion hidden"></span></a><br>`;
    Swal.fire({
        // icon: 'info',
        position: "top",
        title: "<strong>Cambiar contraseña</strong>",
        html: htmlInputs,
        showCloseButton: false,
        showCancelButton: true,
        confirmButtonText: "Cambiar",
        confirmButtonColor: "#3e866f",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#f5365c",
        backdrop: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        focusConfirm: true,
        preConfirm: function () {
            if (document.getElementById("passwordOne").value == "") {
                Swal.showValidationMessage("Ingrese contraseña uno");
            } else if (document.getElementById("passwordTwo").value == "") {
                Swal.showValidationMessage("Ingrese contraseña dos");
            } else if (document.getElementById("passwordOne").value != document.getElementById("passwordTwo").value) {
                Swal.showValidationMessage("Las contraseñas no son las mismas");
            } else if (document.getElementById("passwordOne").value.length < 6) {
                Swal.showValidationMessage("La contraseña no puede ser menor a 6 dígitos");
            } else {
                return [document.getElementById("passwordOne").value, document.getElementById("passwordTwo").value];
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            let password = result.value[0];
            $.ajax({
                data: { peticion: "updatePassword", password: password }, //necesario para enviar archivos
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlController, //url a donde hacemos la peticion
                type: "POST",
                beforeSend: function () {
                    $("#overlayText").text("Actualizando Contraseña...");
                    $(".overlayCargue").fadeIn("slow");
                },
                complete: function () {
                    $(".overlayCargue").fadeOut("slow");
                },
                success: function (result) {
                    var estado = result.status;
                    switch (estado) {
                        case "0":
                            // showToast('Usuario Existente!','El usuario ya se encuentra registrado.','info','derecha',5000);
                            break;

                        case "1":
                            Swal.fire({
                                icon: "success",
                                position: "top",
                                title: "<strong>Actualización Contraseña</strong>",
                                html: "<h5>Se ha actualizado con éxito la contraseña</h5>",
                                showCloseButton: false,
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonText: "Cerrar",
                                cancelButtonColor: "#f5365c",
                                backdrop: true,
                            });
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
