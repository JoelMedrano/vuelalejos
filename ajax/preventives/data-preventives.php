<?php

require_once "../../controllers/curl.controller.php";
require_once "../../controllers/template.controller.php";

class DatatableController
{
    public function data()
    {
        if (!empty($_POST)) {

            /*=============================================
            Capturando y organizando las variables POST de DT
            =============================================*/
            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 

            $orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)

            $orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice

            $orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC

            $start  = $_POST["start"]; //Indicador de primer registro de paginación.

            $length = $_POST['length']; //Indicador de la longitud de la paginación.

            /*=============================================
            El total de registros de la data
            =============================================*/
            $url = "preventives?select=id_preventive&linkTo=date_created_preventive&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "";

            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {

                $totalData = $response->total;
            } else {

                echo '{"data": []}';

                return;
            }

            /*=============================================
           	Búsqueda de datos
            =============================================*/

            $select = "id_preventive,code_preventive,id_user_preventive,id_client_preventive,type_preventive,origin_preventive,destination_preventive,adult_preventive,child_preventive,baby_preventive,hand_luggage_preventive,hold_luggage_preventive,price_preventive,services_preventive,state_preventive,date_created_preventive,displayname_user,name_client,phone_client";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["code_client", "name_client", "date_created_client"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "categories?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                /*=============================================
                Seleccionar datos
                =============================================*/
                $url = "relations?rel=preventives,users,clients&type=preventive,user,client&select=" . $select . "&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

                $data = CurlController::request($url, $method, $fields)->results;

                $recordsFiltered = $totalData;
            }

            /*=============================================
            Cuando la data viene vacía
            =============================================*/

            if (empty($data)) {

                echo '{"data": []}';

                return;
            }

            /*=============================================
            Construimos el dato JSON a regresar
            =============================================*/

            $dataJson = '{

            	"Draw": ' . intval($draw) . ',
            	"recordsTotal": ' . $totalData . ',
            	"recordsFiltered": ' . $recordsFiltered . ',
            	"data": [';

            /*=============================================
            Recorremos la data
            =============================================*/
            foreach ($data as $key => $value) {

                if ($_GET["text"] == "flat") {

                    $actions = "";
                } else {

                    $actions = "";

                    $actions = TemplateController::htmlClean($actions);
                }

                //*Traemos datos de json
                $airportDeparture = file_get_contents("../../views/assets/json/airports.json");
                $airportDeparture = json_decode($airportDeparture, true);

                foreach ($airportDeparture as $key => $value1) {
                    if ($value1["iata"] == $value->origin_preventive) {

                        $origin_preventive = "<div>
                            <p class='mb-0'><strong>IATA: </strong>" . $value1["iata"] . "</p>
                            <p class='mb-0'><strong>Nombre: </strong>" . $value1["name"] . "</p>
                            <p class='mb-0'><strong>Ciudad: </strong>" . $value1["city"] . "</p>
                            <p class='mb-0'><strong>Pais: </strong>" . $value1["country"] . "</p>
                        </div>";
                    }

                    if ($value1["iata"] == $value->destination_preventive) {
                        $destination_preventive = "<div>
                            <p class='mb-0'><strong>IATA: </strong>" . $value1["iata"] . "</p>
                            <p class='mb-0'><strong>Nombre: </strong>" . $value1["name"] . "</p>
                            <p class='mb-0'><strong>Ciudad: </strong>" . $value1["city"] . "</p>
                            <p class='mb-0'><strong>Pais: </strong>" . $value1["country"] . "</p>
                        </div>";
                    }
                }

                $origin_preventive  =  TemplateController::htmlClean($origin_preventive);
                $destination_preventive  =  TemplateController::htmlClean($destination_preventive);

                $code_preventive = $value->code_preventive;
                $displayname_user = $value->displayname_user;
                $name_client = $value->name_client;
                $phone_client = $value->phone_client;
                $origin_preventive = $origin_preventive;
                $destination_preventive = $destination_preventive;
                $price_preventive = number_format($value->price_preventive, 2);
                $date_created_preventive = $value->date_created_preventive;

                $dataJson .= '{ 

            		"code_preventive":"' . $code_preventive . '",
            		"displayname_user":"' . $displayname_user . '",
            		"name_client":"' . $name_client . '",
                    "phone_client":"' . $phone_client . '",
                    "origin_preventive":"' . $origin_preventive . '",
                    "destination_preventive":"' . $destination_preventive . '",
                    "price_preventive":"' . $price_preventive . '",
            		"date_created_preventive":"' . $date_created_preventive . '",
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
