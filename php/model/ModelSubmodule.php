<?php
ini_set('display_errors', 1);
class ModelSubmodule
{
    public static function buscarSubmodulos()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        SM.IdSubmodule,
        M.Description as modulo,
        SM.Description as descripcion,
        SM.IdStatus,
        SM.Submodule
        FROM Submodule SM
        join Module M on M.IdModule = SM.IdModule";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdSubmodule = $data['IdSubmodule'];
                $modulo = $data['modulo'];
                $submodulo = $data['Submodule'];
                $estado = $data['IdStatus'];
                $descripcion = $data['descripcion'];

                if ($estado == 1) {
                    $estado = '<span class="badge badge-success">ACTIVO</span>';
                } else {
                    $estado = '<span class="badge badge-danger">INACTIVO</span>';
                }


                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="datosRegistro(' . $IdSubmodule . ');"  title="Ver"></i>';
                $acciones .= '<i class="ik ik-edit-2 fa-2x" style="cursor: pointer;margin-left:5px;" title="Editar" onclick="editarRegistro(' . $IdSubmodule . ');"></i>';
                $acciones .= '<i class="ik ik-repeat fa-2x" style="cursor: pointer;margin-left:5px;" title="Activar/Desactivar" onclick="cambiarEstado(' . $IdSubmodule . ',' . $data['IdStatus'] . ');"></i>';

                $respuesta = array($IdSubmodule, $modulo, $submodulo, $descripcion, $estado, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function crearSubmodulo($IdModule, $Description, $Submodule)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        $Description = $mysqli->real_escape_string($Description);

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $consultaValidarDescripcion = "SELECT * FROM Submodule where (Submodule like '%$Submodule%')";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "INSERT INTO Submodule(IdModule,Description,IdStatus,Submodule)
				VALUES($IdModule,'$Description',1,'$Submodule')";
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

    public static function cambiarEstado($IdSubmodule, $estado)
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

        $consulta = "UPDATE Submodule SET
        IdStatus = $estado
        where IdSubmodule = $IdSubmodule";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function datosSubmodulo($IdSubmodule)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT * FROM Submodule where IdSubmodule = $IdSubmodule";

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

    public static function editarSubmodulo($IdSubmodule,  $IdModule, $Description, $Submodule)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $Description = $mysqli->real_escape_string($Description);

        $consultaValidarDescripcion = "SELECT 
        * 
        FROM Submodule 
        where (Submodule like '%$Submodule%') and IdSubmodule != $IdSubmodule";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "UPDATE Submodule 
            SET
            IdModule = $IdModule,
            Description = '$Description',
            Submodule ='$Submodule'
            WHERE
            IdSubmodule = $IdSubmodule";

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
}
