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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_details'])) {
        $orderDetails = json_decode($_POST['order_details'], true);

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, item_id, quantity) VALUES (:user_id, :item_id, :quantity)");

        foreach ($orderDetails as $item) {
            if (isset($item['item_id']) && isset($item['quantity'])) {
                $insertSuccess = $stmt->execute([
                    'user_id' => $userId,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity']
                ]);

                if (!$insertSuccess) {
                    $errorInfo = $stmt->errorInfo();
                    echo json_encode(['success' => false, 'message' => 'Failed to insert order for item ID: ' . $item['item_id'] . ' - Error: ' . $errorInfo[2]]);
                    exit();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid order details.']);
                exit();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
