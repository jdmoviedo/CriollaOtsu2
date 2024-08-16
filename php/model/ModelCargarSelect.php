<?php
ini_set('display_errors', 1);
/**
 *
 */
class ModelCargarSelect
{
    public static function cargarModulos()
    {
        require_once(rutaBase . 'php/conexion/conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $sql = "SELECT * FROM Module where IdStatus = 1";
        $html = "";
        $resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($data = mysqli_fetch_array($resultado)) {
                $IdModule = $data['IdModule'];
                $Description = $data['Description'];
                $html .= '<option value="' . $IdModule . '">' . $Description . '</option>';
            }
            $respuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $respuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cargarSubModulos($IdModule = null)
    {
        require_once(rutaBase . 'php/conexion/conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $AndModulo = "";

        if ($IdModule != "") {
            $AndModulo = "AND IdModule = $IdModule";
        }

        $sql = "SELECT * FROM Submodule where IdStatus = 1 $AndModulo";
        $html = "";
        $resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($data = mysqli_fetch_array($resultado)) {
                $IdSubmodule = $data['IdSubmodule'];
                $Description = $data['Description'];
                $html .= '<option value="' . $IdSubmodule . '">' . $Description . '</option>';
            }
            $respuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $respuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cargarRestaurantes()
    {
        require_once(rutaBase . 'php/conexion/conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $sql = "SELECT * FROM Restaurant";
        $html = "";
        $resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($data = mysqli_fetch_array($resultado)) {
                $IdRestaurant = $data['IdRestaurant'];
                $Description = $data['Description'];
                $html .= '<option value="' . $IdRestaurant . '">' . $Description . '</option>';
            }
            $respuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $respuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cargarTipoProductos()
    {
        require_once(rutaBase . 'php/conexion/conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $sql = "SELECT * FROM TypeProduct";
        $html = "";
        $resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($data = mysqli_fetch_array($resultado)) {
                $IdTypeProduct = $data['IdTypeProduct'];
                $Description = $data['Description'];
                $html .= '<option value="' . $IdTypeProduct . '">' . $Description . '</option>';
            }
            $respuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $respuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function cargarIngredientes()
    {
        require_once(rutaBase . 'php/conexion/conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $sql = "SELECT * FROM Ingredient";
        $html = "";
        $resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($data = mysqli_fetch_array($resultado)) {
                $IdIngredient = $data['IdIngredient'];
                $Description = $data['Description'];
                $html .= '<option value="' . $IdIngredient . '">' . $Description . '</option>';
            }
            $respuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $respuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($respuesta);
    }


}
