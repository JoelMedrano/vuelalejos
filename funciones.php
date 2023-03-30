<?php
function miFuncion()
{
    $url = "correlatives?select=id_correlative,actual_correlative&linkTo=code_correlative&equalTo=tk";
    $method = "GET";
    $fields = array();

    $response = CurlController::request($url, $method, $fields);

    $code = $response->results[0];
    $id = $code->id_correlative;
    $avanzar = $code->actual_correlative + 1;

    if ($response->status == 200) {
        $data = "actual_correlative=" . $avanzar;

        //*Solicitud a la API
        $url = "correlatives?id=" . $id . "&nameId=id_correlative&token=" . $_SESSION["admin"]->token_user . "&table=users&suffix=user";
        $method = "PUT";
        $fields = $data;

        $responseCorrelative = CurlController::request($url, $method, $fields);
        if ($responseCorrelative->status == 303 || $responseCorrelative->status == 400) {
            session_destroy();

            echo '<script>
    
            window.location = "/";
            
            </script>';
        }
    }
}
