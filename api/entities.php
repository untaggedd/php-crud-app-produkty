<?php
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? explode('/', trim($_GET['path'],'/')) : [];
$id = isset($path[1]) && is_numeric($path[1]) ? intval($path[1]) : null;

switch ($method) {
    case 'GET':
        if ($id) {
            // GET /entities/{id}
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Produkt nie został znaleziony"]);
            }
        } else {
            // GET /entities
            $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($products);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        // Walidacja danych
        if (empty($data['name']) || !isset($data['price'])) {
            http_response_code(400);
            echo json_encode(["message" => "Nazwa i cena produktu są wymagane"]);
            exit();
        }

        $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data['name'], $data['description'], $data['price']]);
        http_response_code(201);
        echo json_encode(["message" => "Produkt został dodany", "id" => $conn->lastInsertId()]);
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
             if (empty($data['name']) || !isset($data['price'])) {
                http_response_code(400);
                echo json_encode(["message" => "Nazwa i cena produktu są wymagane"]);
                exit();
            }

            $sql = "UPDATE products SET name=?, description=?, price=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['name'], $data['description'], $data['price'], $id]);

            if ($stmt->rowCount() > 0) {
                 echo json_encode(["message" => "Produkt został zaktualizowany"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Nie znaleziono produktu lub nie wprowadzono zmian"]);
            }
        }
        break;

    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Produkt został usunięty"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Nie znaleziono produktu"]);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Metoda niedozwolona"]);
        break;
}
?>