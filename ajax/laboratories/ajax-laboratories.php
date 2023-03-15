<?php

require_once "../../controllers/curl.controller.php";

class AdminsController
{

    public $state;
    public $idLaboratory;
    public $token;

    public function dataState()
    {

        $url = "laboratories?id=" . $this->idLaboratory . "&nameId=id_laboratory&token=" . $this->token . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "state_laboratory=" . $this->state;

        $response = CurlController::request($url, $method, $fields)->status;

        echo json_encode($response);
    }
}

if (isset($_POST["state"])) {
    $state = new AdminsController();
    $state->state = $_POST["state"];
    $state->idLaboratory = $_POST["idLaboratory"];
    $state->token = $_POST["token"];
    $state->dataState();
}
