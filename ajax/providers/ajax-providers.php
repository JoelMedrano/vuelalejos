<?php

require_once "../../controllers/curl.controller.php";

class AdminsController
{

    public $state;
    public $idProvider;
    public $token;

    public function dataState()
    {

        $url = "providers?id=" . $this->idProvider . "&nameId=id_provider&token=" . $this->token . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "state_provider=" . $this->state;

        $response = CurlController::request($url, $method, $fields)->status;

        echo json_encode($response);
    }
}

if (isset($_POST["state"])) {
    $state = new AdminsController();
    $state->state = $_POST["state"];
    $state->idProvider = $_POST["idProvider"];
    $state->token = $_POST["token"];
    $state->dataState();
}
