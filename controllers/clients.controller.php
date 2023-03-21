<?php

class ClientsController
{

    //*Crear Preventivo
    public function create()
    {
        if (isset($_POST["code_preventive"])) {

            if (!empty($_POST["jsonLayovers"])) {

                echo '<script>
    
                /* matPreloader("on");
                fncSweetAlert("loading", "Loading...", ""); */
                
                </script>';

                if (
                    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}$/', $_POST["code_preventive"])
                ) {
                    //*Agrupamos la información 
                    $pcreg_preventive = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    $usreg_preventive = $_SESSION["admin"]->username_user;

                    //*primero registramos el cliente si no existe
                    if (empty($_POST["id_client"])) {

                        $select = "actual_correlative";
                        $url = "correlatives?select=id_correlative,actual_correlative&linkTo=code_correlative&equalTo=cl";
                        $method = "GET";
                        $fields = array();

                        $response = CurlController::request($url, $method, $fields);

                        $tamaño = 5;

                        if ($response->status == 200) {
                            $code = $response->results[0];
                            $maxCode = str_pad($code->actual_correlative, $tamaño, '0', STR_PAD_LEFT);
                        } else {
                            $maxCode = str_pad('1', $tamaño, '0', STR_PAD_LEFT);
                        }

                        $dataClient = array(

                            "code_client"           => $maxCode,
                            "name_client"           => trim(strtoupper($_POST["name_client"])),
                            "phone_client"          => $_POST["phone_client"],
                            "pcreg_client"          =>  $pcreg_preventive,
                            "usreg_client"          =>  $usreg_preventive,
                            "date_created_client"   => date("Y-m-d")
                        );

                        //*Solicitud a la API
                        $url = "clients?token=" . $_SESSION["admin"]->token_user . "&table=users&suffix=user";
                        $method = "POST";
                        $fields = $dataClient;

                        $response = CurlController::request($url, $method, $fields);

                        $id_client = $response->results->lastId;
                    } else {
                        $id_client = $_POST["id_client"];
                    }

                    //*ahora creamos la cabecera del preventivo

                    $data = array(

                        "code_preventive"           => trim(strtoupper($_POST["code_preventive"])),
                        "id_user_preventive"        => $_SESSION["admin"]->id_user,
                        "id_client_preventive"      => $id_client,
                        "origin_preventive"         => $_POST["origin_preventive"],
                        "destination_preventive"    => $_POST["destination_preventive"],
                        "adult_preventive"          => $_POST["adult_preventive"],
                        "child_preventive"          => $_POST["child_preventive"],
                        "baby_preventive"           => $_POST["baby_preventive"],
                        "hand_luggage_preventive"   => $_POST["hand_luggage_preventive"],
                        "hold_luggage_preventive"   => $_POST["hold_luggage_preventive"],
                        "price_preventive"          => $_POST["price_preventive"],
                        "services_preventive"       => trim(strtoupper($_POST["services_preventive"])),
                        "pcreg_preventive"          =>  $pcreg_preventive,
                        "usreg_preventive"          =>  $usreg_preventive,
                        "date_created_preventive"   => date("Y-m-d")

                    );

                    //*Solicitud a la API
                    $url = "preventives?token=" . $_SESSION["admin"]->token_user . "&table=users&suffix=user";
                    $method = "POST";
                    $fields = $data;

                    $response = CurlController::request($url, $method, $fields);

                    $id_preventive = $response->results->lastId;
                    //*Respuesta de la API
                    if ($response->status == 200) {

                        $listaEscalas = json_decode($_POST["jsonLayovers"], true);
                    } else {
                        echo '<script>
    
                            fncFormatInputs();
                            matPreloader("off");
                            fncSweetAlert("close", "", "");
                            fncNotie(3, "Error saving compra");
    
                        </script>';
                    }
                } else {
                    echo '<script>
    
                        fncFormatInputs();
                        matPreloader("off");
                        fncSweetAlert("close", "", "");
                        fncNotie(3, "Field syntax error");

                    </script>';
                }
            } else {

                echo '<script>

					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "No se encontraron escalas");

				</script>';
            }
        }
    }
}
