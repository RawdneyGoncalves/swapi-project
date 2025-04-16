<?php

interface TimeConverterInterface
{
    /**
     * @param string
     * @return int
     */
    public function convertToHours(string $timeString): int;
}