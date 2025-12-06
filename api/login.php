<?php
require '../db.php';

// Rozpoczynamy sesję, aby móc zapisać zalogowanego użytkownika
session_start();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Metoda niedozwolona"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Podaj login i hasło"]);
    exit();
}

$username = $data['username'];
$password = $data['password'];

try {
    // Pobieramy użytkownika z bazy danych po loginie
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sprawdzamy czy użytkownik istnieje I czy hasło pasuje do hasha
    if ($user && password_verify($password, $user['password'])) {
        
        // Zapisujemy ID użytkownika w sesji serwera
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(["message" => "Zalogowano pomyślnie"]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "Błędny login lub hasło"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Błąd serwera: " . $e->getMessage()]);
}
?>