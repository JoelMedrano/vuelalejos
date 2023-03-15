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
            $url = "relations?rel=dbarticles,laboratories,categories&type=dbarticle,laboratory,category&linkTo=date_created_dbarticle&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&select=code_dbarticle&filterTo=state_dbarticle&inTo=1";

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
            $select = "id_dbarticle,code_dbarticle,name_dbarticle,id_category_dbarticle,name_category,id_laboratory_dbarticle,name_laboratory,frac_dbarticle,stkmin_dbarticle,stkmax_dbarticle,id_therapy_dbarticle,id_substance_dbarticle,prescription_dbarticle,verification_dbarticle,state_dbarticle,digemid_dbarticle,specialcode_dbarticle,barcode_dbarticle,date_created_dbarticle";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["code_dbarticle", "name_dbarticle", "name_laboratory", "name_category"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "relations?rel=dbarticles,laboratories,categories&type=dbarticle,laboratory,category&select=" . $select . "&linkTo=" . $value . ",state_dbarticle&search=" . $search . ",1&orderBy=" . $orderBy . "&orderMode=" . $orderType;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "relations?rel=dbarticles,laboratories,categories&type=dbarticle,laboratory,category&select=" . $select . "&linkTo=" . $value . ",state_dbarticle&search=" . $search . ",1&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                $url = "relations?rel=dbarticles,laboratories,categories&type=dbarticle,laboratory,category&select=" . $select . "&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length . "&linkTo=state_dbarticle&equalTo=1";

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

                    $state_dbarticle = $value->state_dbarticle;
                    $prescription_dbarticle = $value->prescription_dbarticle;
                    $actions = "";
                } else {

                    if ($value->state_dbarticle == "2") {

                        $state_dbarticle = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked ><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    } else {

                        $state_dbarticle = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='importArticle(event," . $value->id_dbarticle . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    }

                    if ($value->prescription_dbarticle == "SI") {

                        $prescription_dbarticle = "<span class='badge badge-danger p-2'>SI</span>";
                    } else {

                        $prescription_dbarticle = "<span class='badge badge-success p-2'>NO</span>";
                    }

                    $actions = "";

                    $actions = TemplateController::htmlClean($actions);
                }

                $code_dbarticle = $value->code_dbarticle;
                $name_dbarticle = $value->name_dbarticle;
                $name_category = $value->name_category;
                $name_laboratory = $value->name_laboratory;
                $prescription_dbarticle = $prescription_dbarticle;
                $state_dbarticle = $state_dbarticle;
                $date_created_dbarticle = $value->date_created_dbarticle;

                $dataJson .= '{ 

            		"id_dbarticle":"' . ($start + $key + 1) . '",
            		"code_dbarticle":"' . $code_dbarticle . '",
            		"name_dbarticle":"' . $name_dbarticle . '",
            		"name_category":"' . $name_category . '",
                    "name_laboratory":"' . $name_laboratory . '",
                    "prescription_dbarticle":"' . $prescription_dbarticle . '",
                    "state_dbarticle":"' . $state_dbarticle . '",
            		"date_created_dbarticle":"' . $date_created_dbarticle . '"

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
