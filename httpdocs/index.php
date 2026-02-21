<?php
// 1. CORS Headers (Crucial for Blazor)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle Blazor Preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Include your existing connection
require_once 'config.php'; 

// --- 3. ROUTING SETUP ---
$method = $_SERVER['REQUEST_METHOD']; // <-- This defines it
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$parts = explode('/', $url);
$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;

if ($resource !== 'products') {
    http_response_code(404);
    echo json_encode(["error" => "Resource not found"]);
    exit;
}

// --- 4. RESTFUL METHODS ---
// Switch on the $method variable we defined above
switch ($method) { 
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc() ?: ["error" => "Not found"]);
        } else {
            $result = $conn->query("SELECT * FROM products");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO products (item, price, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $data['item'], $data['price'], $data['quantity']);
        $stmt->execute();
        echo json_encode(["id" => $conn->insert_id, "status" => "Created"]);
        break;

    case 'PUT':
        if (!$id) exit;
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE products SET item=?, price=?, quantity=? WHERE id=?");
        $stmt->bind_param("sdii", $data['item'], $data['price'], $data['quantity'], $id);
        $stmt->execute();
        echo json_encode(["status" => "Updated"]);
        break;

    case 'DELETE':
        if (!$id) exit;
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["status" => "Deleted"]);
        break;
}

$conn->close();
