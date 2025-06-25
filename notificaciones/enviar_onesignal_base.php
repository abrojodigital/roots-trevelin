<?php

function enviarOneSignal($titulo, $contenido, $extra = []) {
    // Configuración de OneSignal
    $appId = getenv('ONESIGNAL_APP_ID');
    $apiKey = getenv('ONESIGNAL_API_KEY');

    $fields = array_merge([
        'app_id' => $appId,
        'headings' => ['en' => $titulo],
        'contents' => ['en' => $contenido],
    ], $extra);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $status === 200 ? json_decode($response, true) : false;
}