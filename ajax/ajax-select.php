<?php

require_once "../controllers/curl.controller.php";

class SelectController
{

    public $data;
    public $select;
    public $table;
    public $suffix;

    public function dataSelect()
    {

        $url = $this->table . "?select=" . $this->select . "&linkTo=" . $this->suffix . "&equalTo=" . $this->data;
        $method = "GET";
        $fields = array();

        $response = CurlController::request($url, $method, $fields);

        echo json_encode($response);
    }


    public function dataSelectRel()
    {

        $url = "relations?rel=" . $this->rel . "&type=" . $this->type . "&select=" . $this->select . "&linkTo=" . $this->linkTo . "&equalTo=" . $this->equalTo . "&orderBy=" . $this->orderBy . "&orderMode=" . $this->orderMode . "&startAt=" . $this->startAt . "&endAt=" . $this->endAt;
        $method = "GET";
        $fields = array();

        $response = CurlController::request($url, $method, $fields);

        echo json_encode($response);
    }
}

if (isset($_POST["data"])) {

    $select = new SelectController();
    $select->data = $_POST["data"];
    $select->select = $_POST["select"];
    $select->table = $_POST["table"];
    $select->suffix = $_POST["suffix"];
    $select->dataSelect();
}

if (isset($_POST["rel"])) {

    $select = new SelectController();
    $select->rel = $_POST["rel"];
    $select->type = $_POST["type"];
    $select->select = $_POST["select"];
    $select->linkTo = $_POST["linkTo"];
    $select->equalTo = $_POST["equalTo"];
    $select->orderBy = $_POST["orderBy"];
    $select->orderMode = $_POST["orderMode"];
    $select->startAt = $_POST["startAt"];
    $select->endAt = $_POST["endAt"];
    $select->dataSelectRel();
}
