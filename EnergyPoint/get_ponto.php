<?php
include 'db.php';

// Buscar os pontos do banco
$pontos = listar_pontos();

// Filtrar apenas os que têm latitude e longitude
$pontos_filtrados = array_filter($pontos, function ($ponto) {
    return !empty($ponto['lat']) && !empty($ponto['lng']);
});

// Retornar como JSON
header('Content-Type: application/json');
echo json_encode(array_values($pontos_filtrados));