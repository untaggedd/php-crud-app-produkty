<?php
// api/login.php

// Blądy w konsoli off
ini_set('display_errors', 0);
error_reporting(E_ALL); 

header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/db.php';

session_start();

// Method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metoda niedozwolona"]);
    exit();
}

// Otrzymujemy JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// is valid? JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Nieprawidłowy format danych"]);
    exit();
}

// Check puste pola
if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Podaj login i hasło"]);
    exit();
}

$username = trim($data['username']);
$password = $data['password'];

try {
    // Szukamy użytkownika
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sprawdzamy haslo
    if ($user && password_verify($password, $user['password'])) {
        
        // Zapisujemy sesje
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Zwracamy sukces
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Zalogowano pomyślnie! Przekierowanie...",
            "user" => [
                "id" => $user['id'],
                "username" => $user['username']
            ]
        ]);
    } else {
        // Bląd autoryzacji
        http_response_code(401); 
        echo json_encode([
            "success" => false, 
            "message" => "Błędny login lub hasło"
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Błąd serwera: " . $e->getMessage()
    ]);
}
?>