<?php

require_once "../../controllers/curl.controller.php";

class AdminsController
{

    public $state;
    public $idSubstance;
    public $token;

    public function dataState()
    {

        $url = "substances?id=" . $this->idSubstance . "&nameId=id_substance&token=" . $this->token . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "state_substance=" . $this->state;

        $response = CurlController::request($url, $method, $fields)->status;

        echo json_encode($response);
    }
}

if (isset($_POST["state"])) {
    $state = new AdminsController();
    $state->state = $_POST["state"];
    $state->idSubstance = $_POST["idSubstance"];
    $state->token = $_POST["token"];
    $state->dataState();
}
