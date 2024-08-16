<?php
ini_set('display_errors', 1);
class ModelTypeProduct
{
    public static function buscarTipoProductos()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        TP.IdTypeProduct,
        TP.Description as descripcion,
        TP.UrlImage
        FROM TypeProduct TP";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdTypeProduct = $data['IdTypeProduct'];
                $descripcion = $data['descripcion'];
                $UrlImage = $data['UrlImage'];

                $image = "";

                if(!empty($UrlImage)){
                    if(file_exists(rutaBase.$UrlImage)){
                        $image = '<i class="fa-solid fa-image fa-2x" style="cursor: pointer;" onclick="verImagen(\'' . $UrlImage . '\');"  title="Ver Imagen"></i>';
                    }
                }

                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="datosRegistro(' . $IdTypeProduct . ');"  title="Ver"></i>';
                $acciones .= '<i class="ik ik-edit-2 fa-2x" style="cursor: pointer;margin-left:5px;" title="Editar" onclick="editarRegistro(' . $IdTypeProduct . ');"></i>';
                $acciones .= '<i class="fa-solid fa-file-arrow-up fa-2x" style="cursor: pointer;margin-left:5px;" title="Subir Imagen" onclick="subirImagen(' . $IdTypeProduct . ',\''.$descripcion.'\');"></i>';

                $respuesta = array($IdTypeProduct, $descripcion, $image, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function crearTipoProducto($Description)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        $Description = $mysqli->real_escape_string($Description);

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $consultaValidarDescripcion = "SELECT * FROM TypeProduct where (Description like '%$Description%')";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {
            $consulta = "INSERT INTO TypeProduct(Description)
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

    public static function datosTipoProducto($IdTypeProduct)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT * FROM TypeProduct where IdTypeProduct = $IdTypeProduct";

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

    public static function editarTipoProducto($IdTypeProduct, $Description)
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
        FROM TypeProduct 
        where (Description like '%$Description%')
        and IdTypeProduct != $IdTypeProduct";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "UPDATE TypeProduct
            SET
            Description = '$Description'
            WHERE
            IdTypeProduct = $IdTypeProduct";
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

    public static function subirImagen($IdTypeProduct, $mimeType, $fileTmp)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        // Guardamos el resultado del externo en la NAS
        if ($mimeType == "image/jpeg") {
            $UrlImage = "img/TypeProduct/".$IdTypeProduct.".jpg";
        }else{
            $UrlImage = "img/TypeProduct/".$IdTypeProduct.".png";
        }

        $UrlImage = $mysqli->real_escape_string($UrlImage);

        move_uploaded_file($fileTmp, rutaBase.$UrlImage);
        
        $consulta = "UPDATE TypeProduct
        SET
        UrlImage = '$UrlImage'
        WHERE
        IdTypeProduct = $IdTypeProduct";
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
