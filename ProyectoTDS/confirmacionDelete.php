<?php

    //DELETE
    if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
        

        $postData = array(
            'id' => $_GET['delete_id'],
        );

        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl_handle, CURLOPT_URL, 'http://localhost/ProyectoHuevito/api/usuarios/delete.php');
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer your_access_token'
        ]);

        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($curl_handle);

        if ($response === false) {
            echo 'Error: ' . curl_error($curl_handle);
        } else {
            $decoded_response = json_decode($response, true);

            echo 'Response: ' . print_r($decoded_response, true);
        }

        curl_close($curl_handle);

    }

?>