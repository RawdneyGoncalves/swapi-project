<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/interfaces/TimeConverterInterface.php';
require_once __DIR__ . '/../src/services/ConsumablesTimeConverter.php';

class ConsumablesTimeConverterTest extends TestCase
{
    private $converter;
    
    protected function setUp(): void
    {
        $this->converter = new ConsumablesTimeConverter();
    }
    
    public function testConvertHours()
    {
        $this->assertEquals(5, $this->converter->convertToHours('5 hours'));
        $this->assertEquals(1, $this->converter->convertToHours('1 hour'));
    }
    
    public function testConvertDays()
    {
        $this->assertEquals(24, $this->converter->convertToHours('1 day'));
        $this->assertEquals(48, $this->converter->convertToHours('2 days'));
    }
    
    public function testConvertWeeks()
    {
        $this->assertEquals(24 * 7, $this->converter->convertToHours('1 week'));
        $this->assertEquals(24 * 14, $this->converter->convertToHours('2 weeks'));
    }
    
    public function testConvertMonths()
    {
        $this->assertEquals(24 * 30, $this->converter->convertToHours('1 month'));
        $this->assertEquals(24 * 60, $this->converter->convertToHours('2 months'));
    }
    
    public function testConvertYears()
    {
        $this->assertEquals(24 * 365, $this->converter->convertToHours('1 year'));
        $this->assertEquals(24 * 730, $this->converter->convertToHours('2 years'));
    }
    
    public function testInvalidInput()
    {
        $this->assertEquals(0, $this->converter->convertToHours('unknown'));
        $this->assertEquals(0, $this->converter->convertToHours('invalid format'));
        $this->assertEquals(0, $this->converter->convertToHours('5 centuries'));
    }
}