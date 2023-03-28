//*Agregamos los input cuado hacen click en agregar
let contador = 0;
$(document).on("click", "#addLayoversIda, #addLayoversRetorno", function () {
    let tipo = $(this).attr("tipo");

    if (tipo == "ida") {
        $(".nuevoLayoverIda").append(
            '<div class="row" id="contIda">' +
                "<!-- Aerolinea -->" +
                '<div class="col-lg-2 form-group">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-sm quitarEscala"><i class="fa fa-times"></i></button></span>' +
                '<select class="nuevoAerolinea form-control select2" idLayover="" name="airline_layover" id="airline_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aerolinea</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "<!-- Aeropuerto Salida -->" +
                '<div class="col-lg-3 ingresoAirPartida">' +
                '<select class="form-control select2 nuevoAirPartida" name="departure_layover" id="departure_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aeropuerto Partida</option>" +
                "</select>" +
                "</div>" +
                "<!-- Fecha Salida -->" +
                '<div class="col-lg-2 form-group ingresoDatePartida">' +
                '<input type="datetime-local" class="form-control form-control-sm nuevoDatePartida" name="date_departure_layover" id="date_departure_layover" tipo="' +
                tipo +
                '">' +
                "</div>" +
                "<!-- Aeropuerto Llegada -->" +
                '<div class="col-lg-3 ingresoAirLlegada">' +
                '<select class="form-control select2 nuevoAirLlegada" name="arrival_layover" id="arrival_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aeropuerto Partida</option>" +
                "</select>" +
                "</div>" +
                "<!-- Fecha Llegada -->" +
                '<div class="col-lg-2 form-group ingresoDateLlegada">' +
                '<input type="datetime-local" class="form-control form-control-sm nuevoDateLlegada" name="date_arrival_layover" id="date_arrival_layover">' +
                "</div>" +
                "</div>"
        );
    } else {
        $(".nuevoLayoverRetorno").append(
            '<div class="row" id="contIda">' +
                "<!-- Aerolinea -->" +
                '<div class="col-lg-2 form-group">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-sm quitarEscala"><i class="fa fa-times"></i></button></span>' +
                '<select class="form-control select2 nuevoAerolinea"  idLayover="" name="airline_layover" id="airline_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aerolinea</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "<!-- Aeropuerto Salida -->" +
                '<div class="col-lg-3 ingresoAirPartida">' +
                '<select class="form-control select2 nuevoAirPartida" name="departure_layover" id="departure_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aeropuerto Partida</option>" +
                "</select>" +
                "</div>" +
                "<!-- Fecha Salida -->" +
                '<div class="col-lg-2 form-group ingresoDatePartida">' +
                '<input type="datetime-local" class="form-control form-control-sm nuevoDatePartida" name="date_departure_layover" id="date_departure_layover"tipo="' +
                tipo +
                '">' +
                "</div>" +
                "<!-- Aeropuerto Llegada -->" +
                '<div class="col-lg-3 ingresoAirLlegada">' +
                '<select class="form-control select2 nuevoAirLlegada" name="arrival_layover" id="arrival_layover' +
                contador +
                '"' +
                " required>" +
                "<option value>Seleccionar Aeropuerto Partida</option>" +
                "</select>" +
                "</div>" +
                "<!-- Fecha Llegada -->" +
                '<div class="col-lg-2 form-group ingresoDateLlegada">' +
                '<input type="datetime-local" class="form-control form-control-sm nuevoDateLlegada" name="date_arrival_layover" id="date_arrival_layover">' +
                "</div>" +
                "</div>"
        );
    }

    let nameAirline = "airline_layover" + contador;

    const selectAirline = document.getElementById(nameAirline);

    function cargarAerolinea() {
        fetch("views/assets/json/airlines.json")
            .then((response) => {
                // Comprueba si la respuesta es correcta
                if (!response.ok) {
                    throw new Error("Error al cargar el archivo JSON");
                }
                // Convierte la respuesta en un objeto JavaScript
                return response.json();
            })
            .then((data) => {
                // Itera sobre el arreglo de opciones y crea un elemento option para cada uno
                data.forEach((opcion) => {
                    const optionElement = document.createElement("option");
                    optionElement.value = opcion.code;
                    optionElement.textContent = opcion.name;

                    // Añade el elemento option al elemento select
                    selectAirline.appendChild(optionElement);
                });
                $("#" + nameAirline).select2();
            })
            .catch((error) => {
                // Muestra un mensaje de error en caso de que haya algún problema
                console.error("Error:", error);
            });
    }

    let nameAirportDeparture = "departure_layover" + contador;
    const selectAirportDeparture =
        document.getElementById(nameAirportDeparture);

    let nameAirportArrival = "arrival_layover" + contador;
    const selectAirportArrival = document.getElementById(nameAirportArrival);

    function cargarAeropuerto() {
        fetch("views/assets/json/airports.json")
            .then((response) => {
                // Comprueba si la respuesta es correcta
                if (!response.ok) {
                    throw new Error("Error al cargar el archivo JSON");
                }
                // Convierte la respuesta en un objeto JavaScript
                return response.json();
            })
            .then((data) => {
                // Itera sobre el arreglo de opciones y crea un elemento option para cada uno
                data.forEach((opcion) => {
                    const optionDeparture = document.createElement("option");
                    optionDeparture.value = opcion.iata;
                    optionDeparture.textContent =
                        opcion.iata +
                        "-" +
                        opcion.name +
                        "-" +
                        opcion.city +
                        "-" +
                        opcion.country;

                    // Añade el elemento option al elemento select
                    selectAirportDeparture.appendChild(optionDeparture);

                    const optionArrival = document.createElement("option");
                    optionArrival.value = opcion.iata;
                    optionArrival.textContent =
                        opcion.iata +
                        "-" +
                        opcion.name +
                        "-" +
                        opcion.city +
                        "-" +
                        opcion.country;

                    // Añade el elemento option al elemento select
                    selectAirportArrival.appendChild(optionArrival);
                });
                $("#" + nameAirportDeparture).select2();
                $("#" + nameAirportArrival).select2();
            })
            .catch((error) => {
                // Muestra un mensaje de error en caso de que haya algún problema
                console.error("Error:", error);
            });
    }

    // Espera 3 segundos (3000 milisegundos) antes de cargar las opciones
    setTimeout(cargarAerolinea, 1000);
    setTimeout(cargarAeropuerto, 1000);

    contador++;

    listarVuelos();
});

//*Activar el evento con cualquier cambio
$(".formularioLayover").on(
    "change",
    "input.nuevoDatePartida, input.nuevoDateLlegada",
    function () {
        listarVuelos();
    }
);

$("#botonPrincipal").on("click", function () {
    $("#btnSubmitPreventive").prop("disabled", false);
    listarVuelos();
});

$("#formularioLayover").on("change", "input, select", function (event) {
    listarVuelos();
});

//*Armar el json para las escalas
function listarVuelos() {
    let listaVuelos = [];

    let aerolinea = $(".nuevoAerolinea");
    let airportDeparture = $(".nuevoAirPartida");
    let dateAirportDeparture = $(".nuevoDatePartida");
    let airportArrival = $(".nuevoAirLlegada");
    let dateAirportArrival = $(".nuevoDateLlegada");

    for (let i = 0; i < aerolinea.length; i++) {
        listaVuelos.push({
            airline: $(aerolinea[i]).val(),
            airportDeparture: $(airportDeparture[i]).val(),
            dateAirportDeparture: $(dateAirportDeparture[i]).val(),
            typeLayover: $(dateAirportDeparture[i]).attr("tipo"),
            airportArrival: $(airportArrival[i]).val(),
            dateAirportArrival: $(dateAirportArrival[i]).val(),
        });
    }

    //console.log("jsonLayovers", JSON.stringify(listaVuelos));
    $("#jsonLayovers").val(JSON.stringify(listaVuelos));
}

//*Cargar Cliente en el formulario
$(".tableClientsModal").on("click", ".addClient", function () {
    var id_client = $(this).attr("id_client");

    var data = new FormData();
    data.append("data", id_client);
    data.append("select", "*");
    data.append("table", "clients");
    data.append("suffix", "id_client");

    $.ajax({
        url: "ajax/ajax-select.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            let objResult = JSON.parse(response);
            let client = objResult.results[0];

            $("#id_client").val(client.id_client);
            $("#code_client").val(client.code_client);
            $("#name_client").val(client.name_client);
            $("#phone_client").val(client.phone_client);
        },
    });

    $("#clientModal").modal("hide");
});

//*quitar escala
$(".formularioLayover").on(
    "click",
    ".quitarEscala, .quitarEscalaB",
    function () {
        $(this).parent().parent().parent().parent().remove();

        if (
            $(".nuevoLayoverIda").children().length == 0 &&
            $(".nuevoLayoverRetorno").children().length == 0
        ) {
            $("#jsonLayovers").val("");
        } else {
            listarVuelos();
        }
    }
);
