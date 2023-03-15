<?php

require_once "../../controllers/curl.controller.php";

class AdminsController
{

    public $state;
    public $idUser;
    public $token;

    public function dataState()
    {

        $url = "users?id=" . $this->idUser . "&nameId=id_user&token=" . $this->token . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "state_user=" . $this->state;

        $response = CurlController::request($url, $method, $fields)->status;

        echo json_encode($response);
    }
}

if (isset($_POST["state"])) {
    $state = new AdminsController();
    $state->state = $_POST["state"];
    $state->idUser = $_POST["idUser"];
    $state->token = $_POST["token"];
    $state->dataState();
}
