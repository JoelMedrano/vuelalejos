<?php

require_once "../../controllers/curl.controller.php";

class AdminsController
{

    public $state;
    public $idTherapy;
    public $token;

    public function dataState()
    {

        $url = "therapies?id=" . $this->idTherapy . "&nameId=id_therapy&token=" . $this->token . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "state_therapy=" . $this->state;

        $response = CurlController::request($url, $method, $fields)->status;

        echo json_encode($response);
    }
}

if (isset($_POST["state"])) {
    $state = new AdminsController();
    $state->state = $_POST["state"];
    $state->idTherapy = $_POST["idTherapy"];
    $state->token = $_POST["token"];
    $state->dataState();
}
