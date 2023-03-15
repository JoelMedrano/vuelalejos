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
            $url = "relations?rel=articles,laboratories&type=article,laboratory&select=*&linkTo=state_article&equalTo=1";

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
            $select = "id_article,code_article,name_article,id_laboratory_article,name_laboratory";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["name_article", "name_laboratory", "name_category"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "relations?rel=articles,laboratories&type=article,laboratory&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "relations?rel=articles,laboratories&type=article,laboratory&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                $url = "relations?rel=articles,laboratories&type=article,laboratory&select=" . $select . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                    $actions = "";
                } else {

                    $actions = "<div class='btn-group'>
                                <button type='button' class='btn btn-primary btn-xs rounded-circle agregarCompra recuperarCompra' idArticle='" . $value->id_article . "' id='" . $value->id_article . "'>
                                    <i class='fa fa-plus-circle'></i>
                                </button>
                            </div>";

                    $actions = TemplateController::htmlClean($actions);
                }

                $code_article = $value->code_article;
                $name_article = $value->name_article;
                $name_laboratory = $value->name_laboratory;

                $dataJson .= '{ 

            		"id_article":"' . ($start + $key + 1) . '",
            		"code_article":"' . $code_article . '",
            		"name_article":"' . $name_article . '",
                    "name_laboratory":"' . $name_laboratory . '",
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
