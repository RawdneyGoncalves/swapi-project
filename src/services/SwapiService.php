<?php
class SwapiService {
    private $apiUrl = 'https://swapi.dev/api';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function calculateStops($distance) {
        $starships = $this->getAllStarships();
        
        $results = [];
        
        foreach ($starships as $starship) {
            $name = $starship['name'];
            $mglt = $starship['MGLT'];
            $consumables = $starship['consumables'];
            
            $stops = $this->calculateStopsForStarship($distance, $mglt, $consumables);
            
            $results[$name] = $stops;
            
            $this->saveResult($name, $distance, $stops);
        }
        
        return $results;
    }
    
    private function getAllStarships() {
        $starships = [];
        $nextPage = "{$this->apiUrl}/starships/";
        
        while ($nextPage) {
            $response = $this->makeRequest($nextPage);
            
            if ($response && isset($response['results'])) {
                $starships = array_merge($starships, $response['results']);
                $nextPage = $response['next'] ?? null;
            } else {
                $nextPage = null;
            }
        }
        
        return $starships;
    }
    
    private function calculateStopsForStarship($distance, $mglt, $consumables) {
        if ($mglt === 'unknown') {
            return 0;
        }
        
        $hours = $this->convertConsumablesToHours($consumables);
        
        if ($hours === 0) {
            return 0;
        }
        
        $autonomy = (int)$mglt * $hours;
        
        return floor($distance / $autonomy);
    }
    
    private function convertConsumablesToHours($consumables) {
        if ($consumables === 'unknown') {
            return 0;
        }
        
        preg_match('/^(\d+)\s+(\w+)$/', $consumables, $matches);
        
        if (count($matches) !== 3) {
            return 0;
        }
        
        $value = (int)$matches[1];
        $unit = strtolower($matches[2]);
        
        if ($value === 1) {
            $unit = rtrim($unit, 's');
        }
        
        switch ($unit) {
            case 'hour':
                return $value;
            case 'day':
                return $value * 24;
            case 'week':
                return $value * 24 * 7;
            case 'month':
                return $value * 24 * 30;
            case 'year':
                return $value * 24 * 365;
            default:
                return 0;
        }
    }
    
    private function saveResult($shipName, $distance, $stops) {
        try {
            $conn = $this->db->connect();
            
            $stmt = $conn->prepare("
                INSERT INTO starship_stops (ship_name, distance, stops, calculated_at)
                VALUES (:ship_name, :distance, :stops, NOW())
            ");
            
            $stmt->bindParam(':ship_name', $shipName);
            $stmt->bindParam(':distance', $distance);
            $stmt->bindParam(':stops', $stops);
            
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
    }
    
    private function makeRequest($url) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SWAPI Application');
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        return json_decode($response, true);
    }
}