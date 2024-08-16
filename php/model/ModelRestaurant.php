<?php
ini_set('display_errors', 1);
class ModelRestaurant
{
    public static function buscarRestaurantes()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        R.IdRestaurant,
        R.Description as descripcion
        FROM Restaurant R";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdRestaurant = $data['IdRestaurant'];
                $descripcion = $data['descripcion'];


                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="datosRegistro(' . $IdRestaurant . ');"  title="Ver"></i>';
                $acciones .= '<i class="ik ik-edit-2 fa-2x" style="cursor: pointer;margin-left:5px;" title="Editar" onclick="editarRegistro(' . $IdRestaurant . ');"></i>';

                $respuesta = array($IdRestaurant, $descripcion, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function crearRestaurante($Description)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        $Description = $mysqli->real_escape_string($Description);

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $consultaValidarDescripcion = "SELECT * FROM Restaurant where (Description like '%$Description%')";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {
            $consulta = "INSERT INTO Restaurant(Description)
            VALUES('$Description')";
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

    public static function datosRestaurante($IdRestaurant)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT * FROM Restaurant where IdRestaurant = $IdRestaurant";

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

    public static function editarRestaurante($IdRestaurant, $Description)
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
        FROM Restaurant 
        where (Description like '%$Description%')
        and IdRestaurant != $IdRestaurant";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "UPDATE Restaurant
            SET
            Description = '$Description'
            WHERE
            IdRestaurant = $IdRestaurant";
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
