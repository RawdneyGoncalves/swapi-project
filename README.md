# SWAPI Starship Stops Calculator

Esta aplicação calcula o número de paradas necessárias para cada nave da Star Wars percorrer uma determinada distância em MGLT (Megalights).

## Design e Arquitetura

Este projeto foi desenvolvido seguindo os princípios SOLID:

1. **Single Responsibility Principle**: Cada classe tem uma responsabilidade única
   - `Starship`: Representa uma nave
   - `ConsumablesTimeConverter`: Converte strings de tempo para horas
   - `StandardStopsCalculator`: Calcula o número de paradas

2. **Open/Closed Principle**: O código está aberto para extensão e fechado para modificação
   - Interfaces como `StopsCalculatorInterface` e `TimeConverterInterface` permitem estender funcionalidades sem modificar o código existente

3. **Liskov Substitution Principle**: As implementações concretas podem ser substituídas por suas interfaces
   - O `SwapiService` trabalha com interfaces, não implementações específicas

4. **Interface Segregation Principle**: Interfaces pequenas e específicas ao invés de uma grande interface
   - `TimeConverterInterface` é específico para conversão de tempo
   - `StopsCalculatorInterface` é específico para cálculo de paradas

5. **Dependency Inversion Principle**: Depende de abstrações, não implementações concretas
   - `StandardStopsCalculator` depende de `TimeConverterInterface`, não da implementação específica

### Design Patterns Utilizados

- **Factory Pattern**: `StarshipFactory` cria objetos `Starship` a partir de dados brutos
- **Strategy Pattern**: Diferentes estratégias de cálculo podem ser implementadas via `StopsCalculatorInterface`

## Estrutura do Projeto

```
src/
├── config/
│   └── database.php
├── entities/
│   └── Starship.php
├── factories/
│   └── StarshipFactory.php
├── interfaces/
│   ├── StopsCalculatorInterface.php
│   └── TimeConverterInterface.php
├── services/
│   ├── ConsumablesTimeConverter.php
│   ├── StandardStopsCalculator.php
│   └── SwapiService.php
tests/
├── ConsumablesTimeConverterTest.php
├── StandardStops