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
            $url = "relations?rel=barticles,articles,therapies,substances&type=barticle,article,therapy,substance&linkTo=date_created_barticle&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&select=code_barticle&filterTo=state_barticle&inTo=1";

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
            $select = "id_barticle,id_article,code_barticle,name_barticle,id_therapy_article,name_therapy,id_substance_article,name_substance,id_therapy_barticle,id_substance_barticle,observation_barticle,location_barticle,usreg_barticle,date_created_barticle";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["name_barticle", "usreg_barticle"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "relations?rel=barticles,articles,therapies,substances&type=barticle,article,therapy,substance&select=" . $select . "&linkTo=" . $value . ",state_barticle&search=" . $search . ",1&orderBy=" . $orderBy . "&orderMode=" . $orderType;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "relations?rel=barticles,articles,therapies,substances&type=barticle,article,therapy,substance&select=" . $select . "&linkTo=" . $value . ",state_barticle&search=" . $search . ",1&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                        $data = CurlController::request($url, $method, $fields)->results;

                        if ($data  == "Not Found") {

                            /* $data = array();
                            $recordsFiltered = count($data); */

                            echo '{"data": []}';

                            return;
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
                $url = "relations?rel=barticles,articles,therapies,substances&type=barticle,article,therapy,substance&select=" . $select . "&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length . "&linkTo=state_barticle&equalTo=1";

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

                if ($value->id_therapy_article == 0) {
                    $name_therapy = "";
                } else {
                    //*terapia sugerida
                    $url = "therapies?select=name_therapy&linkTo=id_therapy&equalTo=" . $value->id_therapy_article;
                    $method = "GET";
                    $fields = array();

                    $therapy = CurlController::request($url, $method, $fields)->results[0];
                    $name_therapy = $therapy->name_therapy;
                }

                if ($value->id_substance_article == 0) {
                    $name_substance = "";
                } else {
                    //*sustancia sugerida
                    $url = "substances?select=name_substance&linkTo=id_substance&equalTo=" . $value->id_substance_article;
                    $method = "GET";
                    $fields = array();

                    $substance = CurlController::request($url, $method, $fields)->results[0];

                    $name_substance = $substance->name_substance;
                }



                if ($_GET["text"] == "flat") {

                    $actions = "";
                } else {

                    $actions = "<a class='btn btn-success btn-xs rounded-circle mr-1 solicitud' idItem='" . $value->id_barticle . "' status='2'>
                                    <i class='fas fa-check-circle'></i>

                                </a>
                                <a class='btn btn-danger btn-xs rounded-circle mr-1 solicitud' idItem='" . $value->id_barticle . "' status='3'>
                                    <i class='fas fa-times'></i>

                                </a>";
                    $actions = TemplateController::htmlClean($actions);
                }

                $code_barticle = $value->code_barticle;
                $name_barticle = $value->name_barticle;
                $name_atherapy = $name_therapy;
                $name_btherapy = $value->name_therapy;
                $name_asubstance = $name_substance;
                $name_bsubstance = $value->name_substance;
                $observation_barticle = $value->observation_barticle;
                $location_barticle = $value->location_barticle;
                $usreg_barticle = $value->usreg_barticle;
                $date_created_barticle = $value->date_created_barticle;

                $dataJson .= '{ 

            		"id_barticle":"' . ($start + $key + 1) . '",
            		"code_barticle":"' . $code_barticle . '",
            		"name_barticle":"' . $name_barticle . '",
                    "name_atherapy":"' . $name_atherapy . '",
                    "name_btherapy":"' . $name_btherapy . '",
                    "name_asubstance":"' . $name_asubstance . '",
                    "name_bsubstance":"' . $name_bsubstance . '",
                    "observation_barticle":"' . $observation_barticle . '",
                    "location_barticle":"' . $location_barticle . '",
                    "usreg_barticle":"' . $usreg_barticle . '",
            		"date_created_barticle":"' . $date_created_barticle . '",
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
