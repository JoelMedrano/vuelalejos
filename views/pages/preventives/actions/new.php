<?php

$select = "actual_correlative";

$url = "correlatives?select=id_correlative,actual_correlative&linkTo=code_correlative&equalTo=pr";
$method = "GET";
$fields = array();

$response = CurlController::request($url, $method, $fields);

$tamaño = 5;

if ($response->status == 200) {
    $code = $response->results[0];
    $maxCode = str_pad($code->actual_correlative, $tamaño, '0', STR_PAD_LEFT);
} else {
    $maxCode = str_pad('1', $tamaño, '0', STR_PAD_LEFT);
}

?>

<div class="card card-dark card-outline">

    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

        <div class="card-header">

            <?php

            /* require_once "controllers/categories.controller.php";

            $create = new CategoriesController();
            $create->create(); */

            ?>

            <div class="col-md-12 offset-md-0">

                <div class="form-group mt-2 row">

                    <!--=====================================
                    Codigo Preventivo
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Cod. Preventivo</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateRepeat(event,'regex','preventives','code_preventive')" name="code_preventive" value="<?php echo $maxCode ?>" required readonly>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Codigo CLIENTE
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Cod. Cliente</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" name="code_client" id="code_client">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Nombre CLIENTE
                    ======================================-->
                    <div class="col-lg-6 form-group">

                        <label>Nombre Cliente</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateJS(event,'regex')" name="name_client" id="name_client" required autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Telefono CLIENTE
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Telefono Cliente</label>

                        <input type="text" class="form-control" pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\/\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúüÁÉÍÓÚÜ ]{1,}" onchange="validateJS(event,'phone')" name="phone_preventive">

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

                            <option value>Seleccionar Aeropuerto Origen</option>

                            <?php foreach ($airport as $key => $value) : ?>

                                <option value="<?php echo $value["iata"] ?>"><?php echo $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"] ?></option>

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

                            <option value>Seleccionar Aeropuerto Destino</option>

                            <?php foreach ($airport as $key => $value) : ?>

                                <option value="<?php echo $value["iata"] ?>"><?php echo $value["iata"] . ' - ' . $value["name"] . ' - ' . $value["city"] . ' - ' . $value["country"] ?></option>

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

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="adult_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Niños
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Niños</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="child_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Bebes
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Bebes</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="baby_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Equipaje de mano
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Equipaje de mano</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="hand_luggage_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Equipaje de bodega
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Equipaje de bodega</label>

                        <input type="number" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="hold_luggage_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Precio
                    ======================================-->
                    <div class="col-lg-2 form-group">

                        <label>Precio</label>

                        <input type="number" step="any" class="form-control" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event,'numbers')" name="price_preventive" autocomplete="off">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                </div>


            </div>

        </div>

        <div class="card-footer">

            <div class="col-md-8 offset-md-2">

                <div class="form-group mt-3">

                    <a href="/preventives" class="btn btn-light border text-left">Back</a>

                    <button type="submit" class="btn bg-dark float-right">Save</button>

                </div>

            </div>

        </div>


    </form>

</div>

<script>
    window.document.title = "Preventivo - Nuevo"
</script>