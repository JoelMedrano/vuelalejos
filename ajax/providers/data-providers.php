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
            $url = "providers?select=id_provider&linkTo=date_created_provider&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "";

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
            $select = "id_provider,code_provider,td_provider,document_provider,bussiness_name_provider,state_provider,date_created_provider";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["document_provider", "bussiness_name_provider"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "providers?select=" . $select . "&linkTo=" . $value . "&search=" . $value;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "providers?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                        $data = CurlController::request($url, $method, $fields)->results;

                        if ($data  == "Not Found") {
                            $data = array();
                            $recordsFiltered = count($data);
                        } else {
                            $data = $data;
                            $recordsFiltered = count($data);
                            break;
                        }
                    }
                } else {

                    echo '{"data": []}';

                    return;
                }
            } else {

                //*Seleccionar datos
                $url = "providers?select=" . $select . "&linkTo=date_created_provider&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                    $td_provider = $value->td_provider;
                    $state_provider = $value->state_provider;
                    $actions = "";
                } else {

                    if ($value->td_provider == "0") {
                        $td_provider = "<span class='badge badge-dark p-2'>Sin Documento</span>";
                    } else if ($value->td_provider == "1") {
                        $td_provider = "<span class='badge badge-success p-2'>DNI</span>";
                    } else if ($value->td_provider == "4") {
                        $td_provider = "<span class='badge badge-dark p-2'>Carnet de extranjeria</span>";
                    } else if ($value->td_provider == "6") {
                        $td_provider = "<span class='badge badge-primary p-2'>RUC</span>";
                    } else if ($value->td_provider == "7") {
                        $td_provider = "<span class='badge badge-dark p-2'>Pasasporte</span>";
                    } else if ($value->td_provider == "A") {
                        $td_provider = "<span class='badge badge-dark p-2'>Cédula Diplomatica</span>";
                    } else {
                        $td_provider = "<span class='badge badge-dark p-2'>Sin Documento</span>";
                    }

                    if ($value->state_provider == "1") {

                        $state_provider = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked onchange='changeState(event," . $value->id_provider . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    } else {

                        $state_provider = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='changeState(event," . $value->id_provider . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    }

                    $actions = "<a href='/providers/edit/" . base64_encode($value->id_provider . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>

                        <i class='fas fa-pencil-alt'></i>

                    </a>";

                    $actions = TemplateController::htmlClean($actions);
                }

                $code_provider = $value->code_provider;
                $td_provider = $td_provider;
                $document_provider = $value->document_provider;
                $bussiness_name_provider = $value->bussiness_name_provider;
                $state_provider = $state_provider;
                $date_created_provider = $value->date_created_provider;

                $dataJson .= '{ 

            		"id_provider":"' . ($start + $key + 1) . '",
            		"code_provider":"' . $code_provider . '",
            		"td_provider":"' . $td_provider . '",
                    "document_provider":"' . $document_provider . '",
                    "bussiness_name_provider":"' . $bussiness_name_provider . '",
                    "state_provider":"' . $state_provider . '",
            		"date_created_provider":"' . $date_created_provider . '",
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
