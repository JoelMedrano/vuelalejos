<?php
session_start();

require_once "../../controllers/curl.controller.php";
require_once "../../controllers/template.controller.php";

class DatatableController
{
    public function data()
    {
        if (!empty($_POST)) {
            /* echo '<pre>';
            print_r($_POST);
            echo '</pre>'; */

            //*Capturando y organizando las variables POST de DT
            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 

            $orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)

            $orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice

            $orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC

            $start  = $_POST["start"]; //Indicador de primer registro de paginación.

            $length = $_POST['length']; //Indicador de la longitud de la paginación.

            //*El total de registros de la data
            $url = "therapies?select=id_therapy&linkTo=date_created_therapy&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "";

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
            $select = "id_therapy,code_therapy,name_therapy,state_therapy,date_created_therapy";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["code_therapy", "name_therapy", "date_created_therapy"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "therapies?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                $url = "therapies?select=" . $select . "&linkTo=date_created_therapy&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                    $state_therapy = $value->state_therapy;

                    $actions = "";
                } else {

                    if ($_SESSION["admin"]->rol_user == "administrador") {
                        if ($value->state_therapy == "1") {

                            $state_therapy = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked onchange='changeState(event," . $value->id_therapy . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                        } else {

                            $state_therapy = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='changeState(event," . $value->id_therapy . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                        }
                        $actions = "<a href='/therapies/edit/" . base64_encode($value->id_therapy . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>
    
                            <i class='fas fa-pencil-alt'></i>
    
                        </a>
                        <a class='btn btn-primary btn-xs rounded-circle mr-1 terapiaPerfil' idItem='" . $value->id_therapy . "'>
    
                            <i class='fas fa-search'></i>
    
                        </a>";
                    } else {

                        $state_therapy = "";
                        $actions = "<a class='btn btn-primary btn-xs rounded-circle mr-1 terapiaPerfil' idItem='" . $value->id_therapy . "'>

                            <i class='fas fa-search'></i>

                        </a>";
                    }



                    $actions = TemplateController::htmlClean($actions);
                }

                $code_therapy = $value->code_therapy;
                $name_therapy = $value->name_therapy;
                $state_therapy = $state_therapy;
                $date_created_therapy = $value->date_created_therapy;

                $dataJson .= '{ 

            		"id_therapy":"' . ($start + $key + 1) . '",
            		"code_therapy":"' . $code_therapy . '",
            		"name_therapy":"' . $name_therapy . '",
                    "state_therapy":"' . $state_therapy . '",
            		"date_created_therapy":"' . $date_created_therapy . '",
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
