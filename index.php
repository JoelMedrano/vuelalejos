<?php

/*=============================================
Mostrar errores
=============================================*/
date_default_timezone_set('America/Lima');
ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "C:/xampp/htdocs/vuelalejos/php_error_log");

/*=============================================
CORS
=============================================*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');

/*=============================================
Requerimientos
=============================================*/

require_once "controllers/template.controller.php";
require_once "controllers/curl.controller.php";

/*=============================================
VENDOR
=============================================*/
require __DIR__ . '/vendor/autoload.php'; // Carga la biblioteca dotenv

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Crea una nueva instancia de Dotenv
$dotenv->load(); // Carga las variables de entorno desde el archivo .env

$index = new TemplateController();
$index->index();
