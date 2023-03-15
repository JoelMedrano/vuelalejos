<?php

require_once "../../controllers/curl.controller.php";
require_once "../../controllers/template.controller.php";

class DatatableController
{
    public function data()
    {
        if (!empty($_POST)) {

            //*Capturando y organizando las variables POST de DT
            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 

            $orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)

            $orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice

            $orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC

            $start  = $_POST["start"]; //Indicador de primer registro de paginación.

            $length = $_POST['length']; //Indicador de la longitud de la paginación.

            //*El total de registros de la data
            $url = "relations?rel=purchases,providers&type=purchase,provider&linkTo=date_created_purchase&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&select=code_purchase";

            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {

                $totalData = $response->total;
            } else {

                echo '{"data": []}';

                return;
            }

            //*Búsqueda de datos
            $select = "id_purchase,code_purchase,id_provider_purchase,bussiness_name_provider,td_purchase,document_purchase,guide_purchase,tp_purchase,state_purchase,total_purchase,id_company_purchase,name_company,date_expiration_purchase,date_created_purchase";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["bussiness_name_provider", "document_purchase", "name_company"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "relations?rel=purchases,providers,companies&type=purchase,provider,company&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "relations?rel=purchases,providers,companies&type=purchase,provider,company&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                        $data = CurlController::request($url, $method, $fields)->results;

                        if ($data  == "Not Found") {

                            $data = array();
                            $recordsFiltered = count($data);
                        } else {

                            $data = $data;
                            $totalData = count($dataTotal);
                            $recordsFiltered = count($dataTotal);

                            break;
                        }
                    }
                } else {

                    echo '{"data": []}';

                    return;
                }
            } else {

                //*Seleccionar datos
                $url = "relations?rel=purchases,providers,companies&type=purchase,provider,company&select=" . $select . "&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                $data = CurlController::request($url, $method, $fields)->results;

                $totalData = $response->total;
                $recordsFiltered = $totalData;
            }

            //*Cuando la data viene vacía
            if (empty($data)) {

                echo '{"data": []}';

                return;
            }

            //*Construimos el dato JSON a regresar
            $dataJson = '{

                "Draw": ' . intval($draw) . ',
                "recordsTotal": ' . $totalData . ',
                "recordsFiltered": ' . $recordsFiltered . ',
                "data": [';

            //*Recorremos la data
            foreach ($data as $key => $value) {

                if ($_GET["text"] == "flat") {

                    $td_purchase = $value->td_purchase;
                    $tp_purchase = $value->tp_purchase;
                    $actions = "";
                } else {

                    if ($value->td_purchase == "01") {
                        $td_purchase = "<span class='badge badge-primary p-2'>Factura</span>";
                    } else if ($value->td_purchase == "03") {
                        $td_purchase = "<span class='badge badge-info p-2'>Boleta</span>";
                    } else {
                        $td_purchase = "<span class='badge badge-dark p-2'>Sin Documento</span>";
                    }

                    if ($value->tp_purchase == "R") {
                        $tp_purchase = "<span class='badge badge-success p-2'>Contado</span>";
                    } else if ($value->tp_purchase == "C") {
                        $tp_purchase = "<span class='badge badge-warning p-2'>Crédito</span>";
                    } else {
                        $tp_purchase = "<span class='badge badge-dark p-2'>Letras</span>";
                    }

                    $actions = "<a href='/purchases/edit/" . base64_encode($value->id_purchase . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>

                                    <i class='fas fa-pencil-alt'></i>

                                </a>";
                    $actions = TemplateController::htmlClean($actions);
                }

                $code_purchase = $value->code_purchase;
                $name_company = $value->name_company;
                $td_purchase = $td_purchase;
                $document_purchase = $value->document_purchase;
                $bussiness_name_provider = $value->bussiness_name_provider;
                $tp_purchase = $tp_purchase;
                $date_expiration_purchase = $value->date_expiration_purchase;
                $total_purchase = $value->total_purchase;
                $date_created_purchase = $value->date_created_purchase;

                $dataJson .= '{ 

            		"id_purchase":"' . ($start + $key + 1) . '",
            		"code_purchase":"' . $code_purchase . '",
            		"name_company":"' . $name_company . '",
            		"td_purchase":"' . $td_purchase . '",
                    "document_purchase":"' . $document_purchase . '",
                    "bussiness_name_provider":"' . $bussiness_name_provider . '",
                    "tp_purchase":"' . $tp_purchase . '",
                    "date_expiration_purchase":"' . $date_expiration_purchase . '",
                    "total_purchase":"' . $total_purchase . '",
            		"date_created_purchase":"' . $date_created_purchase . '",
            		"actions":"' . $actions . '"

            	},';
            }
            $dataJson = substr($dataJson, 0, -1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla

            $dataJson .= ']}';

            echo $dataJson;
        }
    }
}

/*=============================================
Activar función DataTable
=============================================*/
$data = new DatatableController();
$data->data();
