<?php
require_once __DIR__ . '/../interfaces/TimeConverterInterface.php';

class ConsumablesTimeConverter implements TimeConverterInterface
{
    private const TIME_UNITS = [
        'hour' => 1,
        'day' => 24,
        'week' => 24 * 7,
        'month' => 24 * 30,
        'year' => 24 * 365
    ];

    /**
     * @inheritDoc
     */
    public function convertToHours(string $timeString): int
    {
        if ($timeString === 'unknown') {
            return 0;
        }
        
        if (!preg_match('/^(\d+)\s+(\w+)$/', $timeString, $matches)) {
            return 0;
        }
        
        $value = (int)$matches[1];
        $unit = strtolower($matches[2]);

        if ($value === 1 && substr($unit, -1) === 's') {
            $unit = rtrim($unit, 's');
        }
        
        if (!isset(self::TIME_UNITS[$unit])) {
            return 0;
        }
        
        return $value * self::TIME_UNITS[$unit];
    }
}