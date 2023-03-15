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

            /*=============================================
            El total de registros de la data
            =============================================*/
            $url = "laboratories?select=id_laboratory&linkTo=date_created_laboratory&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "";

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
            $select = "id_laboratory,code_laboratory,ruc_laboratory,bussiness_name_laboratory,name_laboratory,address_laboratory,postal_code_laboratory,phone1_laboratory,phone2_laboratory,fax_laboratory,email_laboratory,ceo_laboratory,contact_laboratory,state_laboratory,date_created_laboratory";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["code_laboratory", "ruc_laboratory", "bussiness_name_laboratory", "name_laboratory", "email_laboratory", "contact_laboratory", "date_created_laboratory"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "laboratories?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                }
            } else {

                //*Seleccionar datos
                $url = "laboratories?select=" . $select . "&linkTo=date_created_laboratory&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                $data = CurlController::request($url, $method, $fields)->results;

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

                    $state_laboratory = $value->state_laboratory;

                    $actions = "";
                } else {

                    if ($value->state_laboratory == "1") {

                        $state_laboratory = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked onchange='changeState(event," . $value->id_laboratory . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    } else {

                        $state_laboratory = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='changeState(event," . $value->id_laboratory . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    }

                    $actions = "<a href='/laboratories/edit/" . base64_encode($value->id_laboratory . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>

                    <i class='fas fa-pencil-alt'></i>

                    </a>";

                    $actions = TemplateController::htmlClean($actions);
                }

                $code_laboratory = $value->code_laboratory;
                $ruc_laboratory = $value->ruc_laboratory;
                $bussiness_name_laboratory = $value->bussiness_name_laboratory;
                $name_laboratory = $value->name_laboratory;
                $phone1_laboratory = $value->phone1_laboratory;
                $email_laboratory = $value->email_laboratory;
                $contact_laboratory = $value->contact_laboratory;
                $state_laboratory = $state_laboratory;
                $date_created_laboratory = $value->date_created_laboratory;

                $dataJson .= '{ 

            		"id_laboratory":"' . ($start + $key + 1) . '",
            		"code_laboratory":"' . $code_laboratory . '",
            		"ruc_laboratory":"' . $ruc_laboratory . '",
            		"bussiness_name_laboratory":"' . $bussiness_name_laboratory . '",
                    "name_laboratory":"' . $name_laboratory . '",
                    "phone1_laboratory":"' . $phone1_laboratory . '",
                    "email_laboratory":"' . $email_laboratory . '",
                    "contact_laboratory":"' . $contact_laboratory . '",
                    "state_laboratory":"' . $state_laboratory . '",
            		"date_created_laboratory":"' . $date_created_laboratory . '",
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
