<?php
// dołączenie pliku konfiguracyjnego bazy danych
require 'db.php'; 

header("Content-Type: application/json");

// sprawdzenie metody żądania - tylko POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Metoda niedozwolona (wymagany POST)"]);
    exit();
}

// pobranie danych wejściowych JSON
$data = json_decode(file_get_contents("php://input"), true);

// walidacja danych - czy pola nie są puste
if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Wypełnij wszystkie pola (login i hasło)"]);
    exit();
}

// oczyszczenie danych wejściowych
$username = trim($data['username']);
$password = $data['password'];

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["message" => "Hasło musi mieć co najmniej 6 znaków"]);
    exit();
}

// haszowanie hasła
// PASSWORD_DEFAULT używa obecnie algorytmu bcrypt
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    // przygotowanie zapytania SQL do dodania użytkownika
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    // wykonanie zapytania
    $stmt->execute([$username, $passwordHash]);

    // sukces - kod 201 (Created)
    http_response_code(201);
    echo json_encode(["message" => "Rejestracja zakończona sukcesem!"]);

} catch (PDOException $e) {
    // błęd duplikatu (kod 23000 - naruszenie unikalności)
    if ($e->getCode() == 23000) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Taki użytkownik już istnieje"]);
    } else {
        // inne błędy
        http_response_code(500);
        echo json_encode(["message" => "Błąd bazy danych: " . $e->getMessage()]);
    }
}
?>