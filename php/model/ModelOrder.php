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
        O.IdStatusOrder,
        O.IdPaymentMethod,
        PM.Description as PaymentMethod,
        COALESCE(
            (
                SELECT
                SP.Description
                FROM OrderPayment OP 
                JOIN StatusPayment SP on SP.IdStatusPayment = OP.IdStatusPayment
                WHERE OP.IdOrder = O.IdOrder
                ORDER BY OP.IdOrderPayment DESC
                LIMIT 1
            )
        ,'') as StatusPayment
        FROM `Order` O
        JOIN Restaurant R on R.IdRestaurant = O.IdRestaurant
        JOIN StatusOrder SO ON SO.IdStatusOrder = O.IdStatusOrder
        JOIN PaymentMethod PM ON PM.IdPaymentMethod = O.IdPaymentMethod
        ORDER BY O.IdOrder desc";

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
                $IdPaymentMethod = $data['IdPaymentMethod'];
                $PaymentMethod = $data['PaymentMethod'];
                $StatusPayment = $data['StatusPayment'];

                switch ($IdPaymentMethod) {
                    case 1:
                    case 2:
                        $bgColor = "bg-info";
                        break;
                    case 3:
                        $bgColor = "bg-success";
                        break;
                }

                $txtPayment = '<span class="badge '.$bgColor.'">' . $PaymentMethod . '</span>';
                if($IdPaymentMethod == 3){
                    if(!empty($StatusPayment)){
                        switch ($StatusPayment) {
                            case "Pendiente":
                                $bgColor = "bg-secondary";
                                break;
                            case "Aprobada":
                                $bgColor = "bg-success";
                                break;
                            case "Declinada":
                            case "Anulada":
                            case "Error":
                                $bgColor = "bg-danger";
                                break;
                        }
                        $txtPayment .= '<br><span class="badge '.$bgColor.' mt-5">Transacción ' . $StatusPayment . '</span>';
                    }
                }

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

                if($IdPaymentMethod == 3){
                    $acciones .= '<i class="fa-solid fa-file-invoice-dollar fa-2x" style="cursor: pointer;margin-left:5px;" title="Referencias de Pago" onclick="orderPayment(' . $IdOrder . ');"></i>';
                }

                $respuesta = array($IdOrder, $Restaurant, $DateOrder, $Names, $Phone, $Address, $Email, $Total, $StatusOrder, $txtPayment, $acciones);
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
        COALESCE(GROUP_CONCAT(concat(I.Description,'||',ODHI.Value,'||',ODHI.Quantity) ORDER BY I.Description ASC SEPARATOR '|||'), '') as DetailIngredient
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
            <div class="card card-state card-state-success pt-10 pb-10">
                <div class="row g-0 align-items-center">
                    <div class="col-sm-4 text-center">
                        <h5><strong>Producto</strong></h5>
                    </div>
                    <div class="col-sm-3 text-center">
                        <h5><strong>Precio</strong></h5>
                    </div>                 
                    <div class="col-sm-2 text-center">
                        <h5><strong>Cantidad</strong></h5>
                    </div> 
                    <div class="col-sm-3 text-center">
                        <h5><strong>Subtotal</strong></h5>
                    </div>
                </div>
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
                            $IngredientQuantity = $dataIngredient[2];
                            $IngredientValue = "$ " . number_format(intval($dataIngredient[1])*$IngredientQuantity, 0, ',', '.');
                            if (intval($dataIngredient[1]) > 0) {
                                $htmlIngredient .= '<div class="card-text">' . $IngredientDescription . ' X'.$IngredientQuantity.' : ' . $IngredientValue . '</div>';
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
                    <div class="card card-state card-state-success pt-10 pb-10">
                        <div class="row g-0 align-items-center">
                            <div class="col-sm-2">
                                <img src="' . $UrlImage . '" class="img-fluid rounded-start">
                            </div>
                            <div class="col-sm-2">
                                <div class="card-body">
                                    <strong class="card-title">' . $Description . '</strong>
                                    ' . $htmlIngredient . '
                                    <p class="card-text text-muted">' . $Observation . '</p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Value . '</strong>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Quantity . '</strong>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $SubValue . '</strong>
                                </div>
                            </div>
                        </div>
                    </div>';
                } else {
                    $html .= '
                    <div class="card card-state card-state-success pt-10 pb-10">
                        <div class="row g-0 align-items-center">
                            <div class="col-sm-4">
                                <div class="card-body">
                                    <strong class="card-title">' . $Description . '</strong>
                                    ' . $htmlIngredient . '
                                    <p class="card-text"><small class="text-muted">' . $Observation . '</small></p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Value . '</strong>
                                </div>
                            </div>
                             <div class="col-sm-2">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $Quantity . '</strong>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card-body text-center">
                                    <strong class="card-title">' . $SubValue . '</strong>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            $html .= '
                <div class="card card-state card-state-success">
                    <div class="row g-0">
                        <div class="col-sm-9">
                            <div class="card-body text-end">
                                <strong class="card-title">Valor Total</strong>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card-body text-center">
                                <strong class="card-title">' . $Total . '</strong>
                            </div>
                        </div>
                    </div>
                </div>';
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

    public static function orderPayment($IdOrder)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        OP.DatePayment,
        OP.Amount,
        OP.IdOrderPayment,
        SP.Description as StatusPayment
        FROM OrderPayment OP
        JOIN StatusPayment SP on SP.IdStatusPayment = OP.IdStatusPayment
        WHERE OP.IdOrder = $IdOrder
        ORDER BY OP.IdOrderPayment desc";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            $html = '
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">REFERENCIA</th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">MONTO</th>
                        <th class="text-center">ESTADO DE PAGO</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>';
            while ($data = mysqli_fetch_array($resultado)) {
                $DatePayment = $data["DatePayment"];
                $Amount = $data["Amount"];
                $IdOrderPayment = $data["IdOrderPayment"];
                $StatusPayment = $data["StatusPayment"];
                $Reference = "CO".str_pad($IdOrderPayment,9,'0',STR_PAD_LEFT);
                        
                switch ($StatusPayment) {
                    case "Pendiente":
                        $bgColor = "bg-secondary";
                        break;
                    case "Aprobada":
                        $bgColor = "bg-success";
                        break;
                    case "Declinada":
                    case "Anulada":
                    case "Error":
                        $bgColor = "bg-danger";
                        break;
                }
                $txtPayment = '<span class="badge '.$bgColor.' mt-5">Transacción ' . $StatusPayment . '</span>';
                
                $acciones = '<i class="ik ik-eye fa-2x" style="cursor: pointer;" onclick="logOrderPayment(' . $IdOrder . ','.$IdOrderPayment.',\''.$Reference.'\');"  title="Ver Transacciones"></i>';
                $html .= '
                <tr>
                    <td>' . $Reference . '</td>
                    <td>' . $DatePayment . '</td>
                    <td class="text-end">' . "$ " . number_format(intval($Amount), 0, ',', '.') . '</td>
                    <td>' . $txtPayment . '</td>
                    <td>' . $acciones . '</td>
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

    public static function logOrderPayment($IdOrderPayment)
    {
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d');

        $consulta = "SELECT
        TL.Information
        FROM TransactionLog TL
        WHERE TL.IdOrderPayment = $IdOrderPayment
        ORDER BY TL.IdTransactionLog desc";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        if (mysqli_num_rows($resultado) > 0) {
            $arrayrespuesta['status'] = "1";
            require_once(rutaBase . 'php/libraries/fechas.php');
            $html = '
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">INFORMACIÓN</th>
                    </tr>
                </thead>
                <tbody>';
            while ($data = mysqli_fetch_array($resultado)) {
                $Information = $data["Information"];
                
                $html .= '
                <tr>
                    <td>' . $Information . '</td>
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
