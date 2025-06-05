<?php

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth\Token\Verifier;

function verificar_token_firebase($idToken) {
    // Caminho fixo para o arquivo de credenciais do Firebase Admin SDK
    $credencialPath = 'C:/xampp/firebase/credencial.json';
    $factory = (new Factory)->withServiceAccount($credencialPath);
    $auth = $factory->createAuth();

    try {
        $verifiedIdToken = $auth->verifyIdToken($idToken);
        
        // Pega as claims do token
        $claims = $verifiedIdToken->claims();

        $uid = $claims->get('sub');
        $email = $claims->get('email');
        $displayName = $claims->get('name'); // Esta é a claim padrão para o nome completo

        // Retorna um array com todas as informações necessárias, incluindo o nome
        return [
            'uid' => $uid,
            'email' => $email,
            'name' => $displayName // Retorne o nome aqui
        ];
    } catch (\Throwable $e) {
        // Logar o erro para depuração
        error_log("Erro na verificação do token Firebase: " . $e->getMessage());
        return false;
    }
}
?>