<?php

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
            #$url = "users?select=id_user&linkTo=date_created_user&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&filterTo=rol_user&inTo='admin'";

            $url = "users?select=id_user&linkTo=date_created_user&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "";

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

            $select = "id_user,picture_user,displayname_user,username_user,email_user,rol_user,state_user,id_company_user,ruc_company,name_company,city_company,date_created_user";

            if (!empty($_POST['search']['value'])) {

                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {

                    $linkTo = ["displayname_user", "username_user", "email_user", "rol_user", "ruc_company", "name_company", "city_company", "date_created_user"];

                    $search = str_replace(" ", "_", $_POST['search']['value']);

                    foreach ($linkTo as $key => $value) {

                        $url = "relations?rel=users,companies&type=user,company&select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                $url = "relations?rel=users,companies&type=user,company&linkTo=date_created_user&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&select=" . $select . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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

                    $picture_user = $value->picture_user;
                    $rol_user = $value->rol_user;
                    $state_user = $value->state_user;

                    $actions = "";
                } else {

                    $picture_user = "<img src='" . TemplateController::srcImg() . "views/img/users/" . $value->id_user . "/" . $value->picture_user . "' class='img-circle' style='width:40px'>";


                    if ($value->rol_user == "administrador") {

                        $rol_user = "<span class='badge bg-navy'>Administrador</span>";
                    } else {

                        $rol_user = "<span class='badge bg-purple'>Vendedor</span>";
                    }

                    if ($value->state_user == "1") {

                        $state_user = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' checked onchange='changeState(event," . $value->id_user . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    } else {

                        $state_user = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch" . $key . "' onchange='changeState(event," . $value->id_user . ")'><label class='custom-control-label' for='switch" . $key . "'></label></div>";
                    }

                    $actions = "<a href='/admins/edit/" . base64_encode($value->id_user . "~" . $_GET["token"]) . "' class='btn btn-warning btn-xs mr-1 rounded-circle'>

                    <i class='fas fa-pencil-alt'></i>

                    </a>";

                    $actions = TemplateController::htmlClean($actions);
                }

                $displayname_user = $value->displayname_user;
                $username_user = $value->username_user;
                $email_user = $value->email_user;
                $rol_user = $rol_user;
                $state_user = $state_user;
                $ruc_company = $value->ruc_company;
                $name_company = $value->name_company;
                $city_company = $value->city_company;
                $date_created_user = $value->date_created_user;

                $dataJson .= '{ 

            		"id_user":"' . ($start + $key + 1) . '",
            		"picture_user":"' . $picture_user . '",
            		"displayname_user":"' . $displayname_user . '",
            		"username_user":"' . $username_user . '",
            		"email_user":"' . $email_user . '",
                    "rol_user":"' . $rol_user . '",
            		"state_user":"' . $state_user . '",
            		"ruc_company":"' . $ruc_company . '",
                    "name_company":"' . $name_company . '",
                    "city_company":"' . $city_company . '",
            		"date_created_user":"' . $date_created_user . '",
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
