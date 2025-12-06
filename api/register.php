<?php
// Dołączenie pliku konfiguracyjnego bazy danych
require '../db.php'; 

header("Content-Type: application/json");

// Sprawdzenie metody żądania - dozwolone tylko POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Metoda niedozwolona (wymagany POST)"]);
    exit();
}

// Pobranie danych wejściowych JSON
$data = json_decode(file_get_contents("php://input"), true);

// Walidacja danych - czy pola nie są puste
if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Wypełnij wszystkie pola (login i hasło)"]);
    exit();
}

// Oczyszczenie danych wejściowych
$username = trim($data['username']);
$password = $data['password'];

// Haszowanie hasła - nigdy nie zapisujemy haseł otwartym tekstem!
// PASSWORD_DEFAULT używa obecnie algorytmu bcrypt
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Przygotowanie zapytania SQL do dodania użytkownika
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Wykonanie zapytania
    $stmt->execute([$username, $passwordHash]);

    // Sukces - kod 201 (Created)
    http_response_code(201);
    echo json_encode(["message" => "Rejestracja zakończona sukcesem!"]);

} catch (PDOException $e) {
    // Obsługa błędu duplikatu (kod 23000 w MySQL oznacza naruszenie unikalności)
    if ($e->getCode() == 23000) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Taki użytkownik już istnieje"]);
    } else {
        // Inne błędy serwera
        http_response_code(500);
        echo json_encode(["message" => "Błąd bazy danych: " . $e->getMessage()]);
    }
}
?>