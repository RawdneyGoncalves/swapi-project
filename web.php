<?php
require_once 'src/config/database.php';
require_once 'src/services/SwapiService.php';

$distance = isset($_GET['distance']) ? (int)$_GET['distance'] : '';
$error = '';

if (isset($_GET['calculate'])) {
    if (!isset($_GET['distance']) || $_GET['distance'] === '') {
        $error = 'Por favor, informe uma distância.';
    } elseif ((int)$_GET['distance'] <= 0) {
        $error = 'A distância deve ser um número positivo.';
    }
}
$results = [];

if (isset($_GET['calculate']) && $error === '') {
    $swapiService = new SwapiService();
    $results = $swapiService->calculateStops($distance);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWAPI Starship Stops Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="number"] {
            padding: 8px;
            width: 200px;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .input-display {
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #4CAF50;
        }
    </style>
</head>
<body>
    <h1>SWAPI Starship Stops Calculator</h1>
    
    <form method="get">
        <label for="distance">Distância (MGLT):</label>
        <input type="number" id="distance" name="distance" value="<?php echo htmlspecialchars($distance); ?>" min="1" required>
        <button type="submit" name="calculate" value="1">Calcular</button>
    </form>
    
    <?php if ($error): ?>
        <div style="color: red; margin-top: 10px; text-align: center;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($results)): ?>
        <div class="input-display">
            <strong>ENTRADA:</strong>
            <p>- <?php echo $distance; ?></p>
        </div>
        
        <h2>SAÍDA:</h2>
        <table>
            <thead>
                <tr>
                    <th>Nave</th>
                    <th>Paradas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $shipName => $stops): ?>
                <tr>
                    <td><?php echo htmlspecialchars($shipName); ?></td>
                    <td><?php echo $stops; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>