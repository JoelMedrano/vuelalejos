<?php

class CurlController
{
    /*=============================================
	Peticiones a la API
	=============================================*/

    static public function request($url, $method, $fields)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apimain.vuelalejos.com/' . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Authorization: c5LTA6WPbMwHhEabYu77nN9cn4VcMj'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }


    /*=============================================
	Peticiones al destino de archivos
	=============================================*/

    static public function requestFile($fields)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://vuelalejos.com/views/img/index.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Authorization: c5LTA6WPbMwHhEabYu77nN9cn4VcMj'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /*=============================================
	Función para buscar por DNI
	=============================================*/
    static public function consultaDNI($documento)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://apiperu.net.pe/api/dni/' . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer RFn95FIYnAIMEFW8rwKhldz0XhYB6jVWuVSdnV3n5hYioSxbff',
                'Cookie: apiperu_session=kKLgLv76udrlQhzbuj3lk0RqNJ8FmRZRaHtrLgTP'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }

    /*=============================================
	Función para buscar por DNI
	=============================================*/
    static public function consultaRUC($documento)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://apiperu.net.pe/api/ruc/' . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer PUdDnC9j4bYdfEu5RGT7V0g3upwxayhnNMzoTN8DLqSE4XqKSA',
                'Cookie: apiperu_session=kKLgLv76udrlQhzbuj3lk0RqNJ8FmRZRaHtrLgTP'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }
}
