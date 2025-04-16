<?php

class Starship
{
    private $name;
    private $mglt;
    private $consumables;

    /**
     * @param string
     * @param string
     * @param string
     */
    public function __construct(string $name, string $mglt, string $consumables)
    {
        $this->name = $name;
        $this->mglt = $mglt;
        $this->consumables = $consumables;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMglt(): string
    {
        return $this->mglt;
    }

    /**
     * @return string
     */
    public function getConsumables(): string
    {
        return $this->consumables;
    }

    /**

     * @return bool
     */
    public function hasValidConsumptionData(): bool
    {
        return $this->mglt !== 'unknown' && $this->consumables !== 'unknown';
    }
}