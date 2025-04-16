<?php
require_once __DIR__ . '/../entities/Starship.php';

class StarshipFactory
{
    /**
     * @param array
     * @return Starship
     */
    public static function createFromApiData(array $data): Starship
    {
        $name = $data['name'] ?? 'Unknown';
        $mglt = $data['MGLT'] ?? 'unknown';
        $consumables = $data['consumables'] ?? 'unknown';
        
        return new Starship($name, $mglt, $consumables);
    }
}