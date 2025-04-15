CREATE TABLE IF NOT EXISTS starship_stops (
    id SERIAL PRIMARY KEY,
    ship_name VARCHAR(255) NOT NULL,
    distance BIGINT NOT NULL,
    stops INT NOT NULL,
    calculated_at TIMESTAMP NOT NULL
);

CREATE INDEX idx_ship_name ON starship_stops(ship_name);
CREATE INDEX idx_calculated_at ON starship_stops(calculated_at);