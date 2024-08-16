<?php
ini_set('display_errors', 1);
class ModelProduct
{
    public static function buscarProductos()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        P.IdProduct,
        R.Description as Restaurante,
        TP.Description as TipoProducto,
        P.IdStatusProduct,
        P.Value,
        P.UrlImage,
        P.LongDescription,
        P.Description
        FROM Product P
        join TypeProduct TP On TP.IdTypeProduct = P.IdTypeProduct
        join Restaurant R On R.IdRestaurant = P.IdRestaurant";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdProduct = $data['IdProduct'];
                $Restaurante = $data['Restaurante'];
                $TipoProducto = $data['TipoProducto'];
                $estado = $data['IdStatusProduct'];
                $Description = $data['Description'];
                $UrlImage = $data['UrlImage'];
                $Value = "$ ".number_format($data['Value'],0,',','.');
                $LongDescription = $data['LongDescription'];

                if ($estado == 1) {
                    $estado = '<span class="badge badge-success">ACTIVO</span>';
                } else {
                    $estado = '<span class="badge badge-danger">INACTIVO</span>';
                }

                $image = "";

                if(!empty($UrlImage)){
                    if(file_exists(rutaBase.$UrlImage)){
                        $image = '<i class="fa-solid fa-image fa-2x" style="cursor: pointer;" onclick="verImagen(\'' . $UrlImage . '\');"  title="Ver Imagen"></i>';
                    }
                }


                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="datosRegistro(' . $IdProduct . ');"  title="Ver"></i>';
                $acciones .= '<i class="ik ik-edit-2 fa-2x" style="cursor: pointer;margin-left:5px;" title="Editar" onclick="editarRegistro(' . $IdProduct . ');"></i>';
                $acciones .= '<i class="ik ik-repeat fa-2x" style="cursor: pointer;margin-left:5px;" title="Activar/Desactivar" onclick="cambiarEstado(' . $IdProduct . ',' . $data['IdStatusProduct'] . ');"></i>';
                $acciones .= '<i class="fa-solid fa-boxes-stacked fa-2x" style="cursor: pointer;margin-left:5px;" title="Editar" onclick="ProductHasIngredient(' . $IdProduct . ',\''.$Description.'\');"></i>';
                $acciones .= '<i class="fa-solid fa-file-arrow-up fa-2x" style="cursor: pointer;margin-left:5px;" title="Subir Imagen" onclick="subirImagen(' . $IdProduct . ',\''.$Description.'\');"></i>';

                $respuesta = array($IdProduct, $Restaurante, $TipoProducto, $Description, $LongDescription, $Value, $image, $estado, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function crearProducto($IdRestaurant, $IdTypeProduct, $Description, $LongDescription, $Value)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        $Description = $mysqli->real_escape_string($Description);
        $LongDescription = $mysqli->real_escape_string($LongDescription);

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $consultaValidarDescripcion = "SELECT * FROM Product where (Description like '%$Description%')";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "INSERT INTO Product(IdRestaurant,IdTypeProduct,Description,LongDescription,Value,IdStatusProduct)
			VALUES($IdRestaurant,$IdTypeProduct,'$Description','$LongDescription',$Value,1)";
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

    public static function cambiarEstado($IdProduct, $estado)
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

        $consulta = "UPDATE Product SET
        IdStatusProduct = $estado
        where IdProduct = $IdProduct";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function datosProducto($IdProduct)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT * FROM Product where IdProduct = $IdProduct";

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

    public static function editarProducto($IdProduct, $IdRestaurant, $IdTypeProduct, $Description, $LongDescription, $Value)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        $Description = $mysqli->real_escape_string($Description);
        $LongDescription = $mysqli->real_escape_string($LongDescription);

        $consultaValidarDescripcion = "SELECT 
        * 
        FROM Product 
        where (Description like '%$Description%') and IdProduct != $IdProduct";
        $resultadoValidarDescripcion = mysqli_query($mysqli, $consultaValidarDescripcion) or die("Error en la Consulta SQL: " . $consultaValidarDescripcion);

        if (mysqli_num_rows($resultadoValidarDescripcion) > 0) {
            $respuesta['status'] = "2";
        } else {

            $consulta = "UPDATE Product 
            SET
            IdRestaurant = $IdRestaurant,
            IdTypeProduct = $IdTypeProduct,
            Description = '$Description',
            LongDescription = '$LongDescription',
            Value = $Value
            WHERE
            IdProduct = $IdProduct";

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

    public static function subirImagen($IdProduct, $mimeType, $fileTmp)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');

        // Guardamos el resultado del externo en la NAS
        if ($mimeType == "image/jpeg") {
            $UrlImage = "img/Product/".$IdProduct.".jpg";
        }else{
            $UrlImage = "img/Product/".$IdProduct.".png";
        }

        $UrlImage = $mysqli->real_escape_string($UrlImage);

        move_uploaded_file($fileTmp, rutaBase.$UrlImage);
        
        $consulta = "UPDATE Product
        SET
        UrlImage = '$UrlImage'
        WHERE
        IdProduct = $IdProduct";
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function ProductHasIngredient($IdProduct)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT 
        PHI.Value,
        I.IdIngredient,
        I.Description
        FROM ProductHasIngredient PHI
        JOIN Ingredient I on I.IdIngredient = PHI.IdIngredient
        where PHI.IdProduct = $IdProduct";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $html = '
            <div class="col-6 form-group text-center">
                <strong>Ingrediente</strong>
            </div>
            <div class="col-5 form-group text-center">
                <strong>Valor</strong>
            </div>
            <div class="col-1 form-group">
                
            </div>';
            if (mysqli_num_rows($resultado) > 0) {

                while($data = mysqli_fetch_array($resultado)){
                    $IdIngredient = $data["IdIngredient"];
                    $Description = $data["Description"];
                    $Value = intval($data["Value"]);

                    $html .= '
                    <div class="divIngredient align-items-center d-flex">
                        <div class="col-6 form-group">
                            <p>'.$Description.'</p>
                            <input type="hidden" class="form-control" value="'.$IdIngredient.'">
                        </div>
                        <div class="col-5 form-group">
                            <a class="tooltips">
                                <input type="text" class="form-control requerido maxlength-input numero" title="Valor" placeholder="Valor" minlength="1" maxlength="20" value="'.$Value.'">
                                <span class="spanValidacion hidden"></span>
                            </a>
                        </div>
                        <div class="col-1 form-group text-center">
                            <i class="fa-solid fa-trash cursor-pointer" onclick="deleteProductHasIngredient(this)"></i>
                        </div>
                    </div>
                    ';
                }                

                $respuesta['status'] = "1";
                $respuesta['html'] = $html;
            } else {
                $respuesta['status'] = "2";
                $respuesta['html'] = $html;
            }
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function registrarProductHasIngredient($IdProduct, $Datos)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $usuario = Sesion::GetParametro('id');

        $consulta = "DELETE FROM ProductHasIngredient WHERE IdProduct = $IdProduct";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $consultaInsert = "INSERT INTO ProductHasIngredient(IdProduct,IdIngredient,Value) VALUES ";
            $dataInsert = "";

            for ($i=0; $i < count($Datos); $i++) { 
                $IdIngredient = $Datos[$i]["IdIngredient"];
                $Value = $Datos[$i]["Value"];
                $dataInsert .= " ($IdProduct,$IdIngredient,$Value), ";
            }

            $dataInsert = rtrim($dataInsert, ', ');

            if(!empty($dataInsert)){
                $consultaInsert .= $dataInsert;
                $resultadoInsert = mysqli_query($mysqli, $consultaInsert) or die("Error en la Consulta SQL: " . $consultaInsert);
            }

            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
