<?php
// api/entities.php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require __DIR__ . '/db.php'; 

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// === GET: otrzymujemy liste ===
if ($method === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Błąd: " . $e->getMessage()]);
    }
}

// === POST: dodać nowe ===
elseif ($method === 'POST') {
    if (empty($input['name']) || empty($input['price'])) {
        http_response_code(400);
        echo json_encode(["message" => "Brak wymaganych danych"]);
        exit;
    }
    try {
        $sql = "INSERT INTO products (name, description, price, sku, is_available) VALUES (:name, :description, :price, :sku, :is_available)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $input['name'],
            ':description' => $input['description'] ?? '',
            ':price' => $input['price'],
            ':sku' => $input['sku'] ?? '',
            ':is_available' => $input['is_available'] ?? 1
        ]);
        echo json_encode(["message" => "Dodano pomyślnie"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Błąd: " . $e->getMessage()]);
    }
}

// === PUT: update istniejące ===
elseif ($method === 'PUT') {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Brak ID produktu"]);
        exit;
    }
    try {
        $sql = "UPDATE products SET name=:name, description=:description, price=:price, sku=:sku, is_available=:is_available WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $input['id'],
            ':name' => $input['name'],
            ':description' => $input['description'] ?? '',
            ':price' => $input['price'],
            ':sku' => $input['sku'] ?? '',
            ':is_available' => $input['is_available'] ?? 1
        ]);
        echo json_encode(["message" => "Zaktualizowano pomyślnie"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Błąd: " . $e->getMessage()]);
    }
}

// === DELETE: ===
elseif ($method === 'DELETE') {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Brak ID do usunięcia"]);
        exit;
    }
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(["message" => "Usunięto pomyślnie"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Błąd: " . $e->getMessage()]);
    }
}
?>