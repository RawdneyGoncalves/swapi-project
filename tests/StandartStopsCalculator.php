<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/entities/Starship.php';
require_once __DIR__ . '/../src/interfaces/StopsCalculatorInterface.php';
require_once __DIR__ . '/../src/interfaces/TimeConverterInterface.php';
require_once __DIR__ . '/../src/services/StandardStopsCalculator.php';

class StandardStopsCalculatorTest extends TestCase
{
    private $calculator;
    private $timeConverter;
    
    protected function setUp(): void
    {
        $this->timeConverter = $this->createMock(TimeConverterInterface::class);
        
        $this->calculator = new StandardStopsCalculator($this->timeConverter);
    }
    
    public function testCalculateStopsForValidStarship()
    {
        $this->timeConverter->method('convertToHours')
                           ->willReturn(168);
        $starship = new Starship('Y-wing', '80', '1 week');
        $result = $this->calculator->calculateStops($starship, 1000000);
        
        $this->assertEquals(74, $result);
    }
    
    public function testCalculateStopsForMillenniumFalcon()
    {
        $this->timeConverter->method('convertToHours')
                           ->willReturn(1440);
        $starship = new Starship('Millennium Falcon', '75', '2 months');
        $result = $this->calculator->calculateStops($starship, 1000000);
        
        $this->assertEquals(9, $result);
    }
    
    public function testCalculateStopsWithUnknownMGLT()
    {
        $starship = new Starship('Death Star', 'unknown', '3 years');
        $this->timeConverter->expects($this->never())
                           ->method('convertToHours');
        $result = $this->calculator->calculateStops($starship, 1000000);
        $this->assertEquals(0, $result);
    }
    
    public function testCalculateStopsWithUnknownConsumables()
    {
        $starship = new Starship('X-wing', '100', 'unknown');
        $this->timeConverter->expects($this->once())
                           ->method('convertToHours')
                           ->willReturn(0);
        $result = $this->calculator->calculateStops($starship, 1000000);
        $this->assertEquals(0, $result);
    }
    
    public function testCalculateStopsWithZeroAutonomy()
    {
        $this->timeConverter->method('convertToHours')
                           ->willReturn(0);
        $starship = new Starship('TIE Fighter', '100', 'invalid');
        $result = $this->calculator->calculateStops($starship, 1000000);
        $this->assertEquals(0, $result);
    }
}