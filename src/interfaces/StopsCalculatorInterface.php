<?php

interface StopsCalculatorInterface
{
    /**
     * @param Starship
     * @param int
     * @return int
     */
    public function calculateStops(Starship $starship, int $distance): int;
}