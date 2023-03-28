<?php
session_start();
// Include the main TCPDF library (search for installation path).
require_once('../../extensiones/tcpdf/tcpdf.php');
require_once "../../controllers/curl.controller.php";
require_once "../../controllers/template.controller.php";

class MYPDF extends TCPDF
{
	public function Header()
	{
		//*Obtener valores
		$security = base64_decode($_GET["id"]);
		$id = $security;

		$select = "*";

		$url = "preventives?select=" . $select . "&linkTo=id_preventive&equalTo=" . $id;
		$method = "GET";
		$fields = array();

		$response = CurlController::request($url, $method, $fields);

		$preventives = $response->results[0];

		$url = "clients?select=" . $select . "&linkTo=id_client&equalTo=" . $preventives->id_client_preventive;
		$method = "GET";
		$fields = array();

		$response = CurlController::request($url, $method, $fields);

		$clients = $response->results[0];

		$image_file = '../images/logo_a.png';
		$this->Image($image_file, 10, 10, 40, '', 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', 'B', 15);
		$this->Cell(180, 0, 'PREVENTIVO N° - ' . $preventives->code_preventive, 0, false, 'C', 0, '', 0, false, false, false);

		$this->Ln(30);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(85, 5, 'ASESOR COMERCIAL', 1, false, 'C', 0, '', 0, false, false, false);
		$this->Cell(10, 5, '', 0, false, 'C', 0, '', 0, false, false, false);
		$this->Cell(85, 5, 'DATOS DEL CLIENTE', 1, false, 'C', 0, '', 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Consultor:', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, 'CRISBEL GARCIA', 'RBT', false, 'L', 0, '', 0, false, false, false);
		$this->Cell(10, 5, '', 0, false, 'C', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Nombres', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, $clients->name_client, 'RBT', false, 'L', 0, '', 0, false, false, false);

		$this->Ln(6);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Teléfono:', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, '3899560925', 'RBT', false, 'L', 0, '', 0, false, false, false);
		$this->Cell(10, 5, '', 0, false, 'C', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Teléfono', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, $clients->phone_client, 'RBT', false, 'L', 0, '', 0, false, false, false);

		$this->Ln(6);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Oficina de venta:', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, 'VENTA NO PRESENCIAL', 'RBT', false, 'L', 0, '', 0, false, false, false);
		$this->Cell(10, 5, '', 0, false, 'C', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(25, 5, 'Tipo de Vuelo', 'LBT', false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(60, 5, 'INTERNACIONAL', 'RBT', false, 'L', 0, '', 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, 'Pasajeros:', 'B', false, 'L', 0, '', 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, 'ADULTOS:', 0, false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, $preventives->adult_preventive, 0, false, 'L', 0, '', 0, false, false, false);

		$this->Ln(6);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, 'INFANTES:', 0, false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, $preventives->child_preventive, 0, false, 'L', 0, '', 0, false, false, false);

		$this->Ln(6);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, 'BEBES:', 0, false, 'L', 0, '', 0, false, false, false);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, $preventives->baby_preventive, 0, false, 'L', 0, '', 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(25, 5, 'Itinerario:', 'B', false, 'L', 0, '', 0, false, false, false);

		$this->Ln(10);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(180, 5, 'IDA:', 1, false, 'C', 0, '', 0, false, false, false);

		$this->Ln(10);

		$url = "layovers?select=" . $select . "&linkTo=id_preventive_layover&equalTo=" . $preventives->id_preventive;
		$method = "GET";
		$fields = array();

		$response = CurlController::request($url, $method, $fields);
		$layovers = $response->results;

		foreach ($layovers as $key => $value) {
			if ($value->type_layover == "IDA") {
				$airline_layover = $value->airline_layover;
				$airline = "";

				if (!empty($airline_layover)) {
					$airlines = file_get_contents("../../views/assets/json/airlines.json");
					$airlines = json_decode($airlines, true);

					foreach ($airlines as $key => $valueA) {
						if ($valueA["code"] == $airline_layover) {
							$airline = $valueA["name"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$departure_layover = $value->airport_departure_layover;
				$departure = "";
				if (!empty($departure_layover)) {
					$airport = file_get_contents("../../views/assets/json/airports.json");
					$airport = json_decode($airport, true);

					foreach ($airport as $key => $valueC) {
						if ($valueC["iata"] == $departure_layover) {
							$departure = $valueC["iata"] . ' - ' . $valueC["name"] . ' - ' . $valueC["country"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$arrival_layover = $value->airport_arrival_layover;
				$arrival = "Seleccionar Aeropuerto origen";
				if (!empty($arrival_layover)) {
					$airport = file_get_contents("../../views/assets/json/airports.json");
					$airport = json_decode($airport, true);

					foreach ($airport as $key => $valueC) {
						if ($valueC["iata"] == $arrival_layover) {
							$arrival = $valueC["iata"] . ' - ' . $valueC["name"] . ' - ' . $valueC["country"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$this->SetFont('helvetica', '', 8);
				$this->Cell(50, 5, $airline, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(100, 5, $departure, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(30, 5, $value->date_departure_layover, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Ln(6);
				$this->Cell(50, 5, $airline, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(100, 5, $arrival, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(30, 5, $value->date_arrival_layover, 0, false, 'L', 0, '', 0, false, false, false);

				$this->Ln(5);
				$this->Cell(180, 5, '', 'T', false, 'L', 0, '', 0, false, false, false);
				$this->Ln(5);
			}
		}

		$this->Ln(5);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(180, 5, 'RETORNO:', 1, false, 'C', 0, '', 0, false, false, false);

		$this->Ln(10);

		foreach ($layovers as $key => $value) {
			if ($value->type_layover == "RETORNO") {
				$airline_layover = $value->airline_layover;
				$airline = "";

				if (!empty($airline_layover)) {
					$airlines = file_get_contents("../../views/assets/json/airlines.json");
					$airlines = json_decode($airlines, true);

					foreach ($airlines as $key => $valueA) {
						if ($valueA["code"] == $airline_layover) {
							$airline = $valueA["name"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$departure_layover = $value->airport_departure_layover;
				$departure = "";
				if (!empty($departure_layover)) {
					$airport = file_get_contents("../../views/assets/json/airports.json");
					$airport = json_decode($airport, true);

					foreach ($airport as $key => $valueC) {
						if ($valueC["iata"] == $departure_layover) {
							$departure = $valueC["iata"] . ' - ' . $valueC["name"] . ' - ' . $valueC["country"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$arrival_layover = $value->airport_arrival_layover;
				$arrival = "Seleccionar Aeropuerto origen";
				if (!empty($arrival_layover)) {
					$airport = file_get_contents("../../views/assets/json/airports.json");
					$airport = json_decode($airport, true);

					foreach ($airport as $key => $valueC) {
						if ($valueC["iata"] == $arrival_layover) {
							$arrival = $valueC["iata"] . ' - ' . $valueC["name"] . ' - ' . $valueC["country"];
							break; // Termina el bucle una vez que se encuentra el valor
						}
					}
				}

				$this->SetFont('helvetica', '', 8);
				$this->Cell(50, 5, $airline, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(100, 5, $departure, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(30, 5, $value->date_departure_layover, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Ln(6);
				$this->Cell(50, 5, $airline, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(100, 5, $arrival, 0, false, 'L', 0, '', 0, false, false, false);
				$this->Cell(30, 5, $value->date_arrival_layover, 0, false, 'L', 0, '', 0, false, false, false);

				$this->Ln(5);
				$this->Cell(180, 5, '', 'T', false, 'L', 0, '', 0, false, false, false);
				$this->Ln(5);
			}
		}
	}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage('P', 'A4');
$pdf->setPage(1, true);



//*Obtener valores
$security = base64_decode($_GET["id"]);
$id = $security;

$select = "*";

$url = "preventives?select=" . $select . "&linkTo=id_preventive&equalTo=" . $id;
$method = "GET";
$fields = array();

$response = CurlController::request($url, $method, $fields);

$preventives = $response->results[0];

$pdf->SetTitle('Preventivo - ' . $preventives->code_preventive . '.pdf');

//$pdf->Output('factura.pdf', 'D');
$pdf->Output('Preventivo - ' . $preventives->code_preventive . '.pdf');
