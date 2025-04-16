<?php
// Habilitar exibição de erros para debug em ambiente de desenvolvimento
// Remova ou comente estas linhas em produção
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar apenas as classes necessárias
require_once 'src/config/database.php';
require_once 'src/services/SwapiService.php';

$distance = isset($_GET['distance']) ? (int)$_GET['distance'] : 0;
$error = '';
$results = [];
$showResults = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['distance'])) {
    if ($distance <= 0) {
        $error = "A distância deve ser um número positivo.";
    } else {
        try {
            $swapiService = new SwapiService();
            $results = $swapiService->calculateStops($distance);
            $showResults = true;
            
            // Log para debug
            error_log("Resultados calculados: " . json_encode($results));
        } catch (Exception $e) {
            $error = "Erro ao calcular paradas: " . $e->getMessage();
            error_log("Erro no cálculo: " . $e->getMessage());
        }
    }
}

// Verificar se o cliente aceita HTML
$acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
$wantsPlainText = strpos($acceptHeader, 'text/plain') !== false || 
                  (isset($_GET['format']) && $_GET['format'] === 'text');

if ($wantsPlainText) {
    // Retornar resposta em texto simples
    header('Content-Type: text/plain');
    
    if ($error) {
        echo "Erro: $error\n";
        echo "Use: /?distance=1000000";
        exit;
    }
    
    if ($showResults) {
        echo "ENTRADA:\n- $distance\n\nSAÍDA:\n";
        foreach ($results as $shipName => $stops) {
            echo "- $shipName: $stops\n";
        }
    } else {
        echo "Use: /?distance=1000000";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Paradas Star Wars</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            width: 200px;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
        }
        .results {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        .input-display {
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .ship-list {
            list-style-type: none;
            padding: 0;
        }
        .ship-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        .ship-item:last-child {
            border-bottom: none;
        }
        .ship-name {
            font-weight: 500;
        }
        .ship-stops {
            font-weight: bold;
            color: #3498db;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
        }
        .api-link {
            margin-top: 15px;
            text-align: center;
        }
        .api-link a {
            color: #3498db;
            text-decoration: none;
        }
        .api-link a:hover {
            text-decoration: underline;
        }
        
        /* Estilo para seção de debug em desenvolvimento */
        .debug-info {
            margin-top: 30px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Calculadora de Paradas - Star Wars</h1>
        
        <div class="form-container">
            <form method="get" action="" id="distance-form">
                <input type="number" 
                       id="distance" 
                       name="distance" 
                       placeholder="Digite a distância em MGLT" 
                       value="<?php echo htmlspecialchars($distance ?: ''); ?>" 
                       min="1" 
                       required>
                <button type="submit">Calcular</button>
            </form>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($showResults): ?>
            <div class="results">
                <div class="input-display">
                    Entrada: <?php echo number_format($distance, 0, ',', '.'); ?> MGLT
                </div>
                
                <?php if (empty($results)): ?>
                    <p>Nenhum resultado encontrado. Verifique a conexão com a API.</p>
                <?php else: ?>
                    <ul class="ship-list">
                        <?php foreach ($results as $shipName => $stops): ?>
                            <li class="ship-item">
                                <span class="ship-name"><?php echo htmlspecialchars($shipName); ?></span>
                                <span class="ship-stops"><?php echo $stops; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            
            <div class="api-link">
                <a href="api.php?distance=<?php echo $distance; ?>" target="_blank">Ver em formato JSON</a>
            </div>
        <?php endif; ?>
        
        <div class="footer">
            Dados fornecidos pela <a href="https://swapi.dev/" target="_blank">SWAPI (Star Wars API)</a>
        </div>
        
        <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
        <div class="debug-info">
            <h3>Informações de Debug</h3>
            <p>PHP Version: <?php echo phpversion(); ?></p>
            <p>Distance: <?php echo $distance; ?></p>
            <p>Results Count: <?php echo count($results); ?></p>
            <p>SWAPI API URL: https://swapi.dev/api/starships/</p>
            <p>Error: <?php echo $error ?: 'None'; ?></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>