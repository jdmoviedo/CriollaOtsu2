$(document).ready(function () {
    // Swal.close();
    // actualizarUltimaConexion();
});

//funcion para preguntar si desea seguir conectado
// var seguirConectado = function () {
    // var tiempoRestante = 30;
    // var contadorRegresivoSeguirConectado =	setInterval(function(){
    // 	tiempoRestante -= 1;
    // 	if(tiempoRestante == 0){
    // 		clearInterval(contadorRegresivoSeguirConectado);
    // 	}
    // 	$("#cuentaAtrasDesconexion").html(tiempoRestante);
    // }, (1000));
    // $("#cuentaAtrasDesconexion").html(30);
    // // $("#modalSeguirConectado").modal('show');
    // Swal.fire({
    //     icon: 'info',
    //     position: 'top',
    //     title: '<strong>Aviso de Sesion</strong>',
    //     html: '<h5>Su sesion esta a punto de finalizar</h5>'
    //     +'<p>Han transcurrido cerca de 15 minutos desde tu última interacción con la plataforma.</p>'
    //     +'<p>Para continuar logueado da clic en "Seguir conectado", de lo contrario tu sesión expirará.</p>'
    //     +'<h1 class="text-center"><span class="cuenta-inactividad" id="cuentaAtrasDesconexion">30</span></h1>',
    //     showCloseButton: false,
    //     confirmButtonText: 'Seguir conectado',
    //     confirmButtonColor: '#26b99a',
    //     backdrop: true,
    //     allowOutsideClick: false,
    //     allowEscapeKey: false,
    //     allowEnterKey: false,
    //     timer: tiempoEspera,
    //     timerProgressBar: true
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         // Actualizamos la sesion
    //         actualizarUltimaConexionOnline();
    //     }
    // })
// };

// 600000 milisegundos - 10 minutos
// 900000 milisegundos - 15 minutos
//variables globales para los timer de desconexion
// var contadorCerrarSesion;
// var contadorSeguirConectado;
// var tiempoMaximo = 900000;
// var tiempoEspera = 30000;
// var tiempoMaximoCierreSesion = tiempoMaximo + tiempoEspera;

// function actualizarUltimaConexion() {
    // //reinicio los timer
    // clearTimeout(contadorCerrarSesion);
    // clearTimeout(contadorSeguirConectado);
    // contadorCerrarSesion = setTimeout(function(){
    // 	logout();
    // }, (tiempoMaximoCierreSesion));
    // contadorSeguirConectado = setTimeout(seguirConectado, tiempoMaximo);
// }

// function actualizarUltimaConexionOnline() {
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
// }
