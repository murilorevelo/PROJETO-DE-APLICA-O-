<?php
function enviar_link_redefinicao_firebase($email) {
    $firebaseApiKey = getenv('FIREBASE_API_KEY');
    $url = "https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key=$firebaseApiKey";

    $data = [
        "requestType" => "PASSWORD_RESET",
        "email" => $email
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}
?>