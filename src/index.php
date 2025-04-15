<?php
require_once 'config/database.php';
require_once 'services/SwapiService.php';

$distance = isset($_GET['distance']) ? (int)$_GET['distance'] : 1000000;

$swapiService = new SwapiService();

$results = $swapiService->calculateStops($distance);

$output = "";
foreach ($results as $shipName => $stops) {
    $output .= "- $shipName: $stops\n";
}

header('Content-Type: text/plain');
echo "ENTRADA:\n- $distance\n\nSAÍDA:\n$output";