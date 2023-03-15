<?php
session_start();

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
            $url = "relations?rel=articles,laboratories,categories&type=article,laboratory,category&linkTo=date_created_article&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&select=code_article";

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
            $select = "id_article,code_article,name_article,id_category_article,name_category,id_laboratory_article,name_laboratory,frac_article,stkmin_article,stkmax_article,id_therapy_article,id_substance_article,prescription_article,verification_article,state_article,digemid_article,specialcode_article,barcode_article,date_created_article";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["code_article", "name_article", "name_laboratory", "name_category"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $urlTotal = "relations?rel=articles,laboratories,categories&type=article,laboratory,category&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType;

                        $dataTotal = CurlController::request($urlTotal, $method, $fields)->results;

                        $url = "relations?rel=articles,laboratories,categories&type=article,laboratory,category&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                $url = "relations?rel=articles,laboratories,categories&type=article,laboratory,category&select=" . $select . "&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                    $state_article = $value->state_article;
                    $prescription_article = $value->prescription_article;
                    $actions = "";
                } else {


                    if ($value->prescription_article == "SI") {

                        $prescription_article = "<span class='badge badge-danger p-2'>SI</span>";
                    } else {

                        $prescription_article = "<span class='badge badge-success p-2'>NO</span>";
                    }

                    if ($_SESSION["admin"]->rol_user == "administrador") {

                        if ($value->state_article == "1") {

                            $state_article = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked onchange='changeState(event," . $value->id_article . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                        } else {

                            $state_article = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='changeState(event," . $value->id_article . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                        }

                        $actions = "<a href='/articles/edit/" . base64_encode($value->id_article . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>
    
                            <i class='fas fa-pencil-alt'></i>
    
                        </a>
                        <button class='btn bg-navy btn-xs btnConfigurarArticulo' title='Configuración' data-toggle='modal' data-target='#modalConfigurarArticulo'
                        id_article=" . $value->id_article . " 
                        code_article=" . $value->code_article . ">
                            <i class='fas fa-cogs'></i>
                        </button>";
                    } else {

                        $state_article = "";

                        $actions = "<a class='btn btn-primary btn-xs rounded-circle mr-1 articuloPerfil' idItem='" . $value->id_article . "'>

                            <i class='fas fa-search'></i>

                        </a>
                        <button class='btn bg-navy btn-xs btnConfigurarArticulo' title='Configuración' data-toggle='modal' data-target='#modalConfigurarArticulo'
                        id_article=" . $value->id_article . " 
                        code_article=" . $value->code_article . ">
                            <i class='fas fa-cogs'></i>
                        </button>";
                    }


                    $actions = TemplateController::htmlClean($actions);
                }

                $code_article = $value->code_article;
                $name_article = $value->name_article;
                $name_category = $value->name_category;
                $name_laboratory = $value->name_laboratory;
                $prescription_article = $prescription_article;
                $state_article = $state_article;
                $date_created_article = $value->date_created_article;

                $dataJson .= '{ 

            		"id_article":"' . ($start + $key + 1) . '",
            		"code_article":"' . $code_article . '",
            		"name_article":"' . $name_article . '",
            		"name_category":"' . $name_category . '",
                    "name_laboratory":"' . $name_laboratory . '",
                    "prescription_article":"' . $prescription_article . '",
                    "state_article":"' . $state_article . '",
            		"date_created_article":"' . $date_created_article . '",
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
