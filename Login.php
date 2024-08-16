<!DOCTYPE html>
<html class="no-js" lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login | Criolla Otsu</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="img/favicon.png" type="image/png" />

    <link rel="stylesheet" href="scripts/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' integrity='sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==' crossorigin='anonymous' />
    <link rel="stylesheet" href="scripts/plugins/ionicons/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="scripts/plugins/icon-kit/dist/css/iconkit.min.css">
    <link rel="stylesheet" href="css/theme.min.css">
    <link rel="stylesheet" href="css/tooltips.css">
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/custom.css">
</head>

<body>
    <!-- Overlay Cargue -->
    <!-- Overlay Cargue -->
    <div class="overlayCargue">
        <h3 class="overlayText">
            <img src="img/logo.png" id="logoOverlay" alt="Logo">
            <div class="animated infinite pulse"><span id="overlayText"></span></div>
            <!-- <div class="rotate"><i class="ik ik-refresh-ccw"></i></div> -->
        </h3>
    </div>
    <div class="auth-wrapper">
        <div class="container-fluid h-100">
            <div class="row flex-row h-100 bg-white container-login">
                <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0 container-auth">
                    <div class="authentication-form mx-auto">
                        <div class="logo-centered align-items-center">
                            <a href="Login"><img src="img/logo.png" alt="Logo" id="login-logo"></a>
                        </div>
                        <h3 class="text-center">Iniciar Sesion en Resturantes Criolla - Otsu</h3>
                        <form id="frmLogin">
                            <a class="tooltips">
                                <div class="form-group">
                                    <input type="text" class="form-control requerido" name="usuarioLogin" id="usuarioLogin" placeholder="Usuario" title="Usuario">
                                    <i class="ik ik-user"></i>
                                </div>
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                            <a class="tooltips">
                                <div class="form-group">
                                    <input type="password" class="form-control requerido" name="contraseniaLogin" id="contraseniaLogin" placeholder="Contraseña del Usuario" title="Contraseña del Usuario">
                                    <i class="ik ik-lock"></i>
                                </div>
                                <span class="spanValidacion hidden hidden"></span>
                            </a>
                            <div class="sign-btn text-center">
                                <a class="btn btn-block btn-theme-iconkit" id="btnLoginIngresar" onclick="Login('frmLogin');">Iniciar Sesion</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 p-0 d-md-block d-lg-block">
                    <div class="lavalite-bg">
                        <div class="lavalite-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/vendor/jquery-3.7.1.js"></script>
    <script src="scripts/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sweet Alert -->
    <script src="scripts/plugins/sweetalert/sweetalert2@11.js"></script>
    <script src="scripts/plugins/select2/dist/js/select2.full.min.js"></script>
    <script src="scripts/plugins/select2/dist/js/i18n/es.js"></script>
    <script src="js/vendor/modernizr-2.8.3.min.js?v=<?php echo (rand()); ?>"></script>
    <script src="js/theme.js"></script>
    <!-- Custom Scripts -->
    <script src="scripts/globales.js?v=<?php echo (rand()); ?>"></script>
    <script src="scripts/utilidades.js?v=<?php echo (rand()); ?>"></script>
    <script src="scripts/conf-notificaciones.js?v=<?php echo (rand()); ?>"></script>
    <script src="scripts/validaciones.js?v=<?php echo (rand()); ?>"></script>
    <script src="scripts/Login.js?v=<?php echo (rand()); ?>"></script>
</body>

</html>