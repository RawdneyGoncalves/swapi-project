<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/entities/Starship.php';

class StarshipTest extends TestCase
{
    public function testCreateStarship()
    {
        $name = 'X-wing';
        $mglt = '100';
        $consumables = '1 week';
        
        $starship = new Starship($name, $mglt, $consumables);
        
        $this->assertEquals($name, $starship->getName());
        $this->assertEquals($mglt, $starship->getMglt());
        $this->assertEquals($consumables, $starship->getConsumables());
    }
    
    public function testHasValidConsumptionData()
    {
        $validStarship = new Starship('X-wing', '100', '1 week');
        $this->assertTrue($validStarship->hasValidConsumptionData());
        
        $unknownMgltStarship = new Starship('Death Star', 'unknown', '3 years');
        $this->assertFalse($unknownMgltStarship->hasValidConsumptionData());
        
        $unknownConsumablesStarship = new Starship('TIE Fighter', '100', 'unknown');
        $this->assertFalse($unknownConsumablesStarship->hasValidConsumptionData());
        
        $allUnknownStarship = new Starship('Unknown Ship', 'unknown', 'unknown');
        $this->assertFalse($allUnknownStarship->hasValidConsumptionData());
    }
}