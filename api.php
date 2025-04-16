<?php
require_once 'src/config/database.php';
require_once 'src/services/SwapiService.php';
require_once 'src/interfaces/StopsCalculatorInterface.php';
require_once 'src/services/StandardStopsCalculator.php';
require_once 'src/services/ConsumablesTimeConverter.php';
require_once 'src/factories/StarshipFactory.php';
require_once 'src/entities/Starship.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

if (!isset($_GET['distance'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode([
        'error' => 'Parâmetro \'distance\' não fornecido',
        'usage' => '/api.php?distance=1000000'
    ]);
    exit;
}

$distance = (int)$_GET['distance'];

if ($distance <= 0) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode([
        'error' => 'A distância deve ser um número positivo'
    ]);
    exit;
}

$swapiService = new SwapiService();
$results = $swapiService->calculateStops($distance);

$response = [
    'input' => $distance,
    'results' => []
];

foreach ($results as $shipName => $stops) {
    $response['results'][] = [
        'name' => $shipName,
        'stops' => $stops
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);