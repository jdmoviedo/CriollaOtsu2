<?php
ini_set('display_errors', 1);
class ModelOrder
{
    public static function buscarPedidos()
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        O.IdOrder,
        R.Description as Restaurant,
        O.DateOrder,
        O.Names,
        O.Phone,
        O.Address,
        O.Email,
        O.Total,
        SO.Description as StatusOrder,
        O.IdStatusOrder
        FROM `Order` O
        JOIN Restaurant R on R.IdRestaurant = O.IdRestaurant
        JOIN StatusOrder SO ON SO.IdStatusOrder = O.IdStatusOrder
        ORDER BY O.DateOrder desc";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $IdOrder = $data['IdOrder'];
                $Restaurant = $data['Restaurant'];
                $DateOrder = $data['DateOrder'];
                $Names = $data['Names'];
                $Phone = $data['Phone'];
                $Address = $data['Address'];
                $Email = $data['Email'];
                $Total = "$ " . number_format(intval($data['Total']), 0, ',', '.');
                $StatusOrder = $data['StatusOrder'];
                $IdStatusOrder = $data['IdStatusOrder'];

                $StatusOrder = '<span class="badge badge-success">' . $data['StatusOrder'] . '</span>';

                switch ($IdStatusOrder) {
                    case 1:
                        $IdStatusOrder = 2;
                        break;
                    case 2:
                        $IdStatusOrder = 3;
                        break;
                    case 3:
                        $IdStatusOrder = 4;
                        break;
                    case 4:
                        $IdStatusOrder = 5;
                        break;
                }


                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="detalleOrden(' . $IdOrder . ');"  title="Ver"></i>';

                if ($IdStatusOrder != 5) {
                    $acciones .= '<i class="ik ik-repeat fa-2x" style="cursor: pointer;margin-left:5px;" title="Activar/Desactivar" onclick="cambiarEstado(' . $IdOrder . ',' . $IdStatusOrder . ');"></i>';
                }

                if ($IdStatusOrder != 1) {
                    $acciones .= '<i class="fa-solid fa-file-lines fa-2x" style="cursor: pointer;margin-left:5px;" title="Ver Trazabailidad" onclick="logOrden(' . $IdOrder . ');"></i>';
                }


                $respuesta = array($IdOrder, $Restaurant, $DateOrder, $Names, $Phone, $Address, $Email, $Total, $StatusOrder, $acciones);
                $arrayrespuesta['datos'][] = $respuesta;
            }
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function detalleOrden($IdOrder)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        O.Total,
        P.Description,
        P.UrlImage,
        OD.Value,
        OD.Observation,
        OD.Quantity,
        COALESCE(GROUP_CONCAT(concat(I.Description,'||',ODHI.Value) ORDER BY I.Description ASC SEPARATOR '|||'), '') as DetailIngredient
        FROM `Order` O
        JOIN OrderDetail OD ON OD.IdOrder = O.IdOrder
        JOIN Product P ON P.IdProduct = OD.IdProduct
        LEFT JOIN OrderDetailHasIngredient ODHI ON ODHI.IdOrderDetail = OD.IdOrderDetail
        LEFT JOIN Ingredient I ON I.IdIngredient = ODHI.IdIngredient
        WHERE OD.IdOrder = $IdOrder
        GROUP BY
        O.Total,
        OD.Quantity,
        P.Description,
        P.UrlImage,
        OD.Value,
        OD.Observation";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');

            $html = '
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5><strong>Producto</strong></h5>
                </div>
                <div class="col-md-2 text-center">
                    <h5><strong>Precio</strong></h5>
                </div>                 
                <div class="col-md-2 text-center">
                    <h5><strong>Cantidad</strong></h5>
                </div> 
                <div class="col-md-2 text-center">
                    <h5><strong>Subtotal</strong></h5>
                </div>';

            $Total = 0;
            //$formatoFecha = new fechas();
            while ($data = mysqli_fetch_array($resultado)) {
                $Description = $data['Description'];
                $UrlImage = $data['UrlImage'];
                $Quantity = $data['Quantity'];
                $Value = "$ " . number_format(intval($data['Value']), 0, ',', '.');
                $SubValue = "$ " . number_format(intval($data['Value'] * $Quantity), 0, ',', '.');
                $Total = "$ " . number_format(intval($data['Total']), 0, ',', '.');
                $Observation = $data['Observation'];

                $htmlIngredient = "";
                if (!empty($data['DetailIngredient'])) {
                    $DetailIngredient = explode("|||", $data['DetailIngredient']);

                    if (count($DetailIngredient) > 0) {
                        for ($i = 0; $i < count($DetailIngredient); $i++) {
                            $dataIngredient = explode("||", $DetailIngredient[$i]);
                            $IngredientDescription = $dataIngredient[0];
                            $IngredientValue = "$ " . number_format(intval($dataIngredient[1]), 0, ',', '.');
                            if (intval($dataIngredient[1]) > 0) {
                                $htmlIngredient .= '<div class="card-text">' . $IngredientDescription . ' : ' . $IngredientValue . '</div>';
                            } else {
                                $htmlIngredient .= '<div class="card-text">' . $IngredientDescription . '</div>';
                            }
                        }
                    }
                }

                $bFileImage = false;

                if (!empty($UrlImage)) {
                    if (file_exists(rutaBase . $UrlImage)) {
                        $bFileImage = true;
                    }
                }

                if (!empty($Observation)) {
                    $Observation = "Información Adicional: " . $Observation;
                }



                if ($bFileImage) {
                    $html .= '
                    <div class="card">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-3">
                                <img src="' . $UrlImage . '" class="img-fluid rounded-start">
                            </div>
                            <div class="col-md-3">
                                <div class="card-body">
                                    <strong class="card-title">' . $Description . '</strong>
                                    ' . $htmlIngredient . '
                                    <p class="card-text"><small class="text-muted">' . $Observation . '</small></p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Value . '</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Quantity . '</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $SubValue . '</strong>
                                </div>
                            </div>
                        </div>
                    </div>';
                } else {
                    $html .= '
                    <div class="card">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-6">
                                <div class="card-body">
                                    <strong class="card-title">' . $Description . '</strong>
                                    ' . $htmlIngredient . '
                                    <p class="card-text"><small class="text-muted">' . $Observation . '</small></p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Value . '</strong>
                                </div>
                            </div>
                             <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Quantity . '</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $SubValue . '</strong>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            $html .= '
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="card-body text-end">
                                <strong class="card-title">Valor Total</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card-body text-center">
                                <strong class="card-title">' . $Total . '</strong>
                            </div>
                        </div>
                    </div>
                </div>                
            </>';
            $arrayrespuesta['html'] = $html;
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function cambiarEstado($IdOrder, $IdStatusOrder)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        $IdUser = Sesion::GetParametro('id');

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s');


        $consulta = "UPDATE `Order` SET
        IdStatusOrder = $IdStatusOrder
        where IdOrder = $IdOrder";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if ($resultado) {
            $Changes = "";

            switch ($IdStatusOrder) {
                case 2:
                    $Changes = "SE CAMBIA ESTADO DE GENERADO POR EN PROCESO";
                    break;
                case 3:
                    $Changes = "SE CAMBIA ESTADO DE EN PROCESO POR ENVIADO";
                    break;
                case 4:
                    $Changes = "SE CAMBIA ESTADO DE ENVIADO POR ENTREGADO";
                    break;
            }

            $consultaLog = "INSERT OrderLog (IdOrder,IdUser,Date,Changes)
            VALUES ($IdOrder,$IdUser,'$fechaActual','$Changes')";

            $resultadoLog = mysqli_query($mysqli, $consultaLog) or die("Error en la Consulta SQL: " . $consultaLog);

            if ($resultadoLog) {
                $respuesta['status'] = "1";
            }
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function logOrden($IdOrder)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        OL.Date,
        U.Names,
        OL.Changes
        FROM OrderLog OL
        JOIN User U on U.IdUser = OL.IdUser
        WHERE OL.IdOrder = $IdOrder";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            $html = '
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">USUARIO</th>
                        <th class="text-center">DESCRIPCION</th>
                    </tr>
                </thead>
                <tbody>';
            while ($data = mysqli_fetch_array($resultado)) {
                $Date = $data["Date"];
                $Names = $data["Names"];
                $Changes = $data["Changes"];
                $html .= '
                <tr>
                    <td>' . $Date . '</td>
                    <td>' . $Names . '</td>
                    <td>' . $Changes . '</td>
                </tr>';
            }
            $html .= '
                </tbody>
                </table>
            </div>';

            $arrayrespuesta = array(
                'status' => '1',
                'html' => $html
            );
        } else {
            $arrayrespuesta['status'] = "0";
        }
        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }
}
