<?php

require_once "../controllers/curl.controller.php";

class ValidateController
{

    public $data;
    public $table;
    public $suffix;

    public function dataRepeat()
    {

        $url = $this->table . "?select=" . $this->suffix . "&linkTo=" . $this->suffix . "&equalTo=" . urlencode($this->data);

        $method = "GET";
        $fields = array();

        $response = CurlController::request($url, $method, $fields);

        echo $response->status;
    }

    public $tipo;
    public $documento;

    public function dataConsulta()
    {

        $tipo = $this->tipo;
        $documento = $this->documento;

        if ($tipo == "1") {
            $response = CurlController::consultaDNI($documento);
        } else {
            $response = CurlController::consultaRUC($documento);
        }

        if ($response->success == "1") {

            echo json_encode($response);
        } else {

            echo "error";
        }
    }
}

if (isset($_POST["data"])) {

    $validate = new ValidateController();
    $validate->data = $_POST["data"];
    $validate->table = $_POST["table"];
    $validate->suffix = $_POST["suffix"];
    $validate->dataRepeat();
}

if (isset($_POST["tipo"])) {

    $validate = new ValidateController();
    $validate->tipo = $_POST["tipo"];
    $validate->documento = $_POST["documento"];
    $validate->dataConsulta();
}
