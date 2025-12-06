CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, -- Pole tekstowe
    price DECIMAL(10, 2) NOT NULL, -- Pole liczbowe (cena)
    description TEXT,              -- Dodatkowe pole tekstowe
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Pole z datą
);