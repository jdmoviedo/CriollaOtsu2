<?php
ini_set('display_errors', 1);
class ModelUser
{
    public static function buscarUsuarios()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        US.IdUser,
        US.Username ,
        US.Names, 
        US.IdStatusUser
        FROM User US
        order by US.IdUser";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdUser = $data['IdUser'];
                $Names = $data['Names'];
                $Username = $data['Username'];
                $IdStatusUser = $data['IdStatusUser'];

                if ($IdStatusUser == 1) {
                    $estado = '<span class="badge badge-success">ACTIVO</span>';
                } else {
                    $estado = '<span class="badge badge-danger">INACTIVO</span>';
                }

                $acciones = '<i class="ik ik-eye fa-2x cursor-pointer" onclick="datosRegistro(' . $IdUser . ');"  title="Ver"></i>';
                $acciones .= '<i class="ik ik-edit-2 fa-2x cursor-pointer ml-5" title="Editar" onclick="editarRegistro(' . $IdUser . ');"></i>';
                $acciones .= '<i class="ik ik-repeat fa-2x cursor-pointer ml-5" title="Activar/Desactivar" onclick="cambiarEstado(' . $IdUser . ',' . $data['IdStatusUser'] . ');"></i>';
                $acciones .= '<i class="fas fa-cubes fa-2x cursor-pointer ml-5" onclick="showModalAsignarSubmodulo(' . $IdUser . ');"  title="Asignar Submodulo"></i>';                

                $respuesta = array($IdUser, $Username, $Names, $estado, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function crearUsuario($Names, $UserName, $Password)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $Names = $mysqli->real_escape_string($Names);
        $UserName = $mysqli->real_escape_string($UserName);
        $Password = $mysqli->real_escape_string($Password);


        $consultaValidarUserName = "SELECT * FROM User where UserName = '$UserName'";
        $resultadoValidarUserName = mysqli_query($mysqli, $consultaValidarUserName) or die("Error en la Consulta SQL: " . $consultaValidarUserName);

        if (mysqli_num_rows($resultadoValidarUserName) > 0) {
            $respuesta['status'] = "2";
        } else {
            $hash = password_hash($Password, PASSWORD_DEFAULT);
            $consulta = "INSERT INTO User(Names,UserName,Password,IdStatusUser,Home)
            VALUES('$Names','$UserName','$hash',1,4)";
            $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

            if ($resultado) {
                $respuesta['status'] = "1";
            } else {
                $respuesta['status'] = "0";
            }
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cambiarEstado($IdUser, $estado)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        if ($estado == 1) {
            $estado = 2;
        } else {
            $estado = 1;
        }

        $consulta = "UPDATE User SET
                    IdStatusUser = $estado
                    where IdUser = $IdUser";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function datosUsuario($IdUser)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT * FROM User where IdUser = $IdUser";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            if (mysqli_num_rows($resultado) > 0) {
                $row = mysqli_fetch_assoc($resultado);
                foreach ($row as $key => $value) {
                    $respuesta[$key] = $value;
                }

                $respuesta['status'] = "1";
            } else {
                $respuesta['status'] = "2";
            }
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function editarUsuario($IdUser, $Names)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $Names = $mysqli->real_escape_string($Names);


        $consulta = "UPDATE User 
        SET
        Names = '$Names'
        WHERE
        IdUser = $IdUser";
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function asignarSubmodulo($IdUser, $home, $submodulos)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');


        $consulta = "DELETE FROM UserHasSubmodule where IdUser = $IdUser";
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";

            for ($i = 0; $i < count($submodulos); $i++) {
                $consultaSubmodulos = "INSERT INTO UserHasSubmodule(IdUser,IdSubmodule)
                VALUES($IdUser,$submodulos[$i])";
                $resultadoSubmodulos = mysqli_query($mysqli, $consultaSubmodulos) or die("Error en la Consulta SQL: " . $consultaSubmodulos);
            }

            $consultaUsuario = "UPDATE User SET
            Home = $home
            where IdUser = $IdUser";

            $resultadoUsuario = mysqli_query($mysqli, $consultaUsuario) or die("Error en la Consulta SQL: " . $consultaUsuario);
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cargarAsignacion($IdUser)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consultaModulos = "SELECT * FROM Module";

        $resultadoModulos = mysqli_query($mysqli, $consultaModulos) or die("Error en la Consulta SQL: " . $consultaModulos);

        if ($resultadoModulos) {
            if (mysqli_num_rows($resultadoModulos) > 0) {

                $html = '<div class="col-md-4 form-group text-center"><b>MODULOS</b></div>';
                $html .= '<div class="col-md-8 form-group text-center"><b>SUBMODULOS</b></div>';

                while ($dataModulos = mysqli_fetch_array($resultadoModulos)) {
                    $IdModule = $dataModulos["IdModule"];
                    $modulo = $dataModulos["Description"];
                    $html .= '<div class="col-md-4 form-group text-capitalize"><input class="chkModulos" value="' . $IdModule . '" type="checkbox" id="modulo' . $IdModule . '"> ' . $modulo . '</div>';
                    $html .= '<div class="col-md-8 form-group"><select class="form-control" name="selectSubModulos[]" id="selectModulo' . $IdModule . '" style="width: 100%;" disabled onchange="todos(this.id)"></select></div>';
                }

                $arrayAsignados = [];

                $consultaAsignados = "SELECT 
                SM.IdModule,
                SM.IdSubmodule,
                U.Home
                FROM UserHasSubmodule UHS 
                join Submodule SM on SM.IdSubmodule = UHS.IdSubmodule
                join User U on U.IdUser = UHS.IdUser
                where UHS.IdUser = $IdUser";
                $resultadoAsignados = mysqli_query($mysqli, $consultaAsignados) or die("Error en la Consulta SQL: " . $consultaAsignados);

                while ($datosAsignados = mysqli_fetch_array($resultadoAsignados)) {
                    $IdModule = $datosAsignados["IdModule"];
                    $IdSubmodule = $datosAsignados["IdSubmodule"];
                    $respuesta['Home'] = $datosAsignados["Home"];
                    $arrayAsignados[$IdModule][] = $IdSubmodule;
                }
                $respuesta['asignados'] = $arrayAsignados;
                $respuesta['status'] = "1";
                $respuesta['html'] = $html;
            } else {
                $respuesta['status'] = "2";
            }
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
