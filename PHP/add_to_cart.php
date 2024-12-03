<?php
session_start();
$host = 'localhost';
$dbname = 'food_ordering';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit();
    }

    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit();
    }

    $userId = $user['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
        $itemId = $_POST['item_id'];
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = :id");
        $stmt->execute(['id' => $itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (user_id, item_id, quantity) 
                VALUES (:user_id, :item_id, 1)
                ON DUPLICATE KEY UPDATE quantity = quantity + 1
            ");
            $stmt->execute(['user_id' => $userId, 'item_id' => $itemId]);
            echo json_encode(['success' => true, 'message' => 'Item added to cart successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
