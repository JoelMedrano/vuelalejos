<?php
session_start();
require_once "../controllers/curl.controller.php";

class SelectController
{

    public function dataCorrelative()
    {

        $url = "correlatives?select=id_correlative,actual_correlative&linkTo=code_correlative&equalTo=" . $this->codigo;
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
        }

        echo json_encode($responseCorrelative->status);
    }
}

if (isset($_POST["codigo"])) {

    $select = new SelectController();
    $select->codigo = $_POST["codigo"];
    $select->dataCorrelative();
}
