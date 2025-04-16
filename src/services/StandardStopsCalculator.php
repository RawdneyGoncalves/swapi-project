<?php
require_once __DIR__ . '/../interfaces/StopsCalculatorInterface.php';
require_once __DIR__ . '/../interfaces/TimeConverterInterface.php';
require_once __DIR__ . '/../entities/Starship.php';

class StandardStopsCalculator implements StopsCalculatorInterface
{
    private $timeConverter;
    
    /**
     * @param TimeConverterInterface
     */
    public function __construct(TimeConverterInterface $timeConverter)
    {
        $this->timeConverter = $timeConverter;
    }
    
    /**
     * @inheritDoc
     */
    public function calculateStops(Starship $starship, int $distance): int
    {
        if (!$starship->hasValidConsumptionData()) {
            return 0;
        }
        
        $mglt = (int)$starship->getMglt();
        $consumables = $starship->getConsumables();
        
        $hours = $this->timeConverter->convertToHours($consumables);

        if ($hours === 0) {
            return 0;
        }

        $autonomy = $mglt * $hours;
        return floor($distance / $autonomy);
    }
}