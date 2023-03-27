<?php
if (isset($routesArray[3])) {

    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["admin"]->token_user) {

        $select = $_ENV['SELECT_PREVENTIVES_FULL'];

        $url = "preventives?select=" . $select . "&linkTo=id_preventive&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();

        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {

            $preventive = $response->results[0];

            $url = "clients?select=*&linkTo=id_client&equalTo=" . $preventive->id_client_preventive;
            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);

            $client = $response->results[0];

            $id_client = $client->id_client;
            $code_client = $client->code_client;
            $name_client = $client->name_client;
            $phone_client = $client->phone_client;

            $origin_preventive = $preventive->origin_preventive;
            $origin = "Seleccionar Aeropuerto origen";
            if (!empty($origin_preventive)) {
                $airport = file_get_contents("views/assets/json/airports.json");
                $airport = json_decode($airport, true);

                foreach ($airport as $key => $value) {
                    if ($value["iata"] == $origin_preventive) {
                        $origin = $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"];
                        break; // Termina el bucle una vez que se encuentra el valor
                    }
                }
            }

            $destination_preventive = $preventive->destination_preventive;
            $destination = "Seleccionar Aeropuerto origen";
            if (!empty($destination_preventive)) {
                $airport = file_get_contents("views/assets/json/airports.json");
                $airport = json_decode($airport, true);

                foreach ($airport as $key => $value) {
                    if ($value["iata"] == $destination_preventive) {
                        $destination = $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"];
                        break; // Termina el bucle una vez que se encuentra el valor
                    }
                }
            }

            $adult_preventive = $preventive->adult_preventive;
            $child_preventive = $preventive->child_preventive;
            $baby_preventive = $preventive->baby_preventive;

            $hand_luggage_preventive = $preventive->hand_luggage_preventive;
            $hold_luggage_preventive = $preventive->hold_luggage_preventive;

            $price_preventive = $preventive->price_preventive;
            $services_preventive = $preventive->services_preventive;

            $select = $_ENV['SELECT_LAYOVERS_FULL'];

            $url = "layovers?select=" . $select . "&linkTo=id_preventive_layover&equalTo=" . $security[0];
            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);

            $layover = $response->results;
            echo '<pre>';
            print_r($layover);
            echo '</pre>';
        } else {

            echo '<script>
                window.location = "/preventives";
            </script>';
        }
    } else {

        echo '<script>
            window.location = "/preventives";
        </script>';
    }
}
?>

<div class="card card-dark card-outline">

    <form method="post" class="needs-validation formularioLayover" id="formularioLayover" novalidate enctype="multipart/form-data">

        <div class="card-header">

            <?php
            /* require_once "controllers/clients.controller.php";

            $create = new ClientsController();
            $create->create(); */
            ?>

            <div class="col-md-12 offset-md-0">

                <div class="form-group mt-2 row">

                    <!--=====================================
                    Codigo Preventivo
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Cod. Preventivo</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateRepeat(event,'regex','preventives','code_preventive')" name="code_preventive" value="<?php echo $preventive->code_preventive ?>" required readonly>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Codigo CLIENTE
                    ======================================-->
                    <div class="col-lg-2 form-group">
                        <label for="code_client">Cod. Cliente</label>
                        <div class="input-group">
                            <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" name="code_client" id="code_client" value="<?php echo $code_client ?>" aria-describedby="btnCodeClient" readonly>
                            <input type="hidden" name="id_client" id="id_client" value="<?php echo $id_client ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btnCodeClient" data-toggle="modal" data-target="#clientModal"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!--=====================================
                    Nombre CLIENTE
                    ======================================-->
                    <div class="col-lg-6 form-group">

                        <label>Nombre Cliente</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateJS(event,'regex')" name="name_client" id="name_client" value="<?php echo $name_client ?>" required autocomplete="off" readonly>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Telefono CLIENTE
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Telefono Cliente</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateJS(event,'phone')" name="phone_client" value="<?php echo $phone_client ?>" id="phone_client" readonly>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Punto de Origen
                    ======================================-->
                    <div class="col-lg-6 form-group">

                        <label>Aeropuerto de Origen</label>

                        <?php

                        $airport = file_get_contents("views/assets/json/airports.json");
                        $airport = json_decode($airport, true);

                        ?>

                        <select class="form-control select2" name="origin_preventive" id="origin_preventive" required>


                            <?php foreach ($airport as $key => $value) : ?>

                                <?php if ($value["iata"] == $origin_preventive) : ?>

                                    <option value="<?php echo $origin_preventive ?>" selected><?php echo $origin  ?></option>
                                <?php else : ?>

                                    <option value="<?php echo $value["iata"] ?>"><?php echo $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"] ?></option>
                                <?php endif ?>

                            <?php endforeach ?>

                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Punto de Destino
                    ======================================-->
                    <div class="col-lg-6 form-group">

                        <label>Aeropuerto de Destino</label>

                        <?php

                        $airport = file_get_contents("views/assets/json/airports.json");
                        $airport = json_decode($airport, true);

                        ?>

                        <select class="form-control select2" name="destination_preventive" id="destination_preventive" required>

                            <?php foreach ($airport as $key => $value) : ?>

                                <?php if ($value["iata"] == $destination_preventive) : ?>

                                    <option value="<?php echo $destination_preventive ?>" selected><?php echo $destination  ?></option>
                                <?php else : ?>

                                    <option value="<?php echo $value["iata"] ?>"><?php echo $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"] ?></option>
                                <?php endif ?>

                            <?php endforeach ?>

                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Adultos
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Adultos</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="adult_preventive" value="<?php echo $adult_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Niños
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Niños</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="child_preventive" value="<?php echo $child_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Bebes
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Bebes</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="baby_preventive" value="<?php echo $baby_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Equipaje de mano
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Equipaje de mano</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="hand_luggage_preventive" value="<?php echo $hand_luggage_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Equipaje de bodega
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Equipaje de bodega</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="hold_luggage_preventive" value="<?php echo $hold_luggage_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Precio
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Precio</label>

                        <input type="number" step="any" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="price_preventive" value="<?php echo $price_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Servicios adiconales
                    ======================================-->
                    <div class="col-lg-6 form-group">

                        <label>Servicios Adicionales</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" name="services_preventive" value="<?php echo $services_preventive ?>" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Boton Agregar
                    ======================================-->
                    <div class="col-lg-2 form-group mt-4">

                        <button type="button" class="btn btn-primary" id="addLayoversIda" tipo="ida">
                            <i class="fas fa-plus"></i> Agregar Escala Ida
                        </button>

                    </div>

                    <div class="col-lg-2 form-group mt-4">

                        <button type="button" class="btn btn-danger" id="addLayoversRetorno" tipo="retorno">
                            <i class="fas fa-plus"></i> Agregar Escala Retorno
                        </button>

                    </div>

                    <div class="col-lg-2 form-group mt-4">

                        <button type="button" class="btn btn-success" id="botonPrincipal">
                            <i class="fas fa-search"></i> Revisar
                        </button>

                    </div>

                </div>

                <!--=====================================
                TITULOS IDA
                ======================================-->
                <div class="card card-primary card-outline">

                    <div class="row">

                        <div class="col-lg-2">

                            <label>Aerolinea</label>

                        </div>

                        <div class="col-lg-3">

                            <label for="">Aeropuerto Partida</label>

                        </div>

                        <div class="col-lg-2">

                            <label for="">Fecha Partida</label>

                        </div>

                        <div class="col-lg-3">

                            <label for="">Aeropuerto Llegada</label>

                        </div>

                        <div class="col-lg-2">

                            <label for="">Fecha Llegada</label>

                        </div>

                    </div>

                </div>

                <!--=====================================
                CUERPO IDA
                ======================================-->
                <div class="form-group nuevoLayoverIda">

                    <?php foreach ($layover as $key => $value) : ?>
                        <?php if ($value->type_layover == "IDA") : ?>
                            <div class="row" id="contIda">
                                <!-- Aerolinea -->
                                <div class="col-lg-2 form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <button type="button" class="btn btn-danger btn-sm quitarEscala"><i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                        <select class="nuevoAerolinea form-control select2" idLayover="" name="airline_layover" id="airline_layover" required>
                                            <option value>Seleccionar Aerolinea</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Aeropuerto Salida -->
                                <div class="col-lg-3 ingresoAirPartida">
                                    <select class="form-control select2 nuevoAirPartida" name="departure_layover" id="departure_layover" required>
                                        <option value>Seleccionar Aeropuerto Partida</option>
                                    </select>
                                </div>
                                <!-- Fecha Salida -->
                                <div class="col-lg-2 form-group ingresoDatePartida">
                                    <input type="datetime-local" class="form-control form-control-sm nuevoDatePartida" name="date_departure_layover" id="date_departure_layover" tipo="">
                                </div>
                                <!-- Aeropuerto Llegada -->
                                <div class="col-lg-3 ingresoAirLlegada">
                                    <select class="form-control select2 nuevoAirLlegada" name="arrival_layover" id="arrival_layover" required>
                                        <option value>Seleccionar Aeropuerto Partida</option>
                                    </select>
                                </div>
                                <!-- Fecha Llegada -->
                                <div class="col-lg-2 form-group ingresoDateLlegada">
                                    <input type="datetime-local" class="form-control form-control-sm nuevoDateLlegada" name="date_arrival_layover" id="date_arrival_layover">
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>


                </div>

                <!--=====================================
                TITULOS RETORNO
                ======================================-->
                <div class="card card-danger card-outline">

                    <div class="row">

                        <div class="col-lg-2">

                            <label>Aerolinea</label>

                        </div>

                        <div class="col-lg-3">

                            <label for="">Aeropuerto Partida</label>

                        </div>

                        <div class="col-lg-2">

                            <label for="">Fecha Partida</label>

                        </div>

                        <div class="col-lg-3">

                            <label for="">Aeropuerto Llegada</label>

                        </div>

                        <div class="col-lg-2">

                            <label for="">Fecha Llegada</label>

                        </div>

                    </div>

                </div>

                <!--=====================================
                CUERPO RETORNO
                ======================================-->
                <div class="form-group nuevoLayoverRetorno">

                </div>

            </div>

            <input type="hidden" id="jsonLayovers" name="jsonLayovers">

        </div>

        <div class="card-footer">

            <div class="col-md-8 offset-md-2">

                <div class="form-group mt-3">

                    <a href="/preventives" class="btn btn-light border text-left">Back</a>

                    <button type="submit" id="btnSubmitPreventive" class="btn bg-dark float-right" disabled>Save</button>

                </div>

            </div>

        </div>


    </form>

</div>

<!-- MODAL PRODUCCION -->
<?php
if (isset($_GET["start"]) && isset($_GET["end"])) {

    $between1 = $_GET["start"];
    $between2 = $_GET["end"];
} else {

    $between1 = date("Y-m-d", strtotime("-100000 day", strtotime(date("Y-m-d"))));
    $between2 = date("Y-m-d");
}
?>
<div class="modal fade" id="clientModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form role="form" method="post">

                <input type="hidden" id="between1" value="<?php echo $between1 ?>">
                <input type="hidden" id="between2" value="<?php echo $between2 ?>">

                <div class="modal-header">
                    <h4 class="modal-title">Clientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="col-lg-12">
                        <div class="card-body">
                            <table id="adminsTable" class="table table-bordered table-striped tableClientsModal">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Telefono</th>
                                        <th>E-mail</th>
                                        <th>Creacion</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="views/assets/custom/datatable/datatable.js"></script>
<script src="views/pages/preventives/preventives.js"></script>

<script>
    window.document.title = "Preventivo - Nuevo"
</script>