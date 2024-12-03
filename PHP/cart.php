<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: User_Login.php");
    exit();
}

$host = 'localhost';
$dbname = 'food_ordering';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    $userId = $user['id'];
    $cartItems = [];

    $stmt = $pdo->prepare("SELECT ci.id AS cart_item_id, mi.id AS item_id, mi.item_name, mi.price, ci.quantity, mi.image
                           FROM cart_items ci
                           JOIN menu_items mi ON ci.item_id = mi.id
                           WHERE ci.user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orders = [];

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute(['user_id' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT mi.item_name, mi.price, ci.quantity
                                   FROM cart_items ci
                                   JOIN menu_items mi ON ci.item_id = mi.id
                                   WHERE ci.user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
            $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orderItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, item_name, price, quantity)
                                       VALUES (:user_id, :item_name, :price, :quantity)");
                $stmt->execute([
                    'user_id' => $userId,
                    'item_name' => $item['item_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ]);
            }

            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);

            $pdo->commit();

            $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC");
            $stmt->execute(['user_id' => $userId]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error placing order: " . $e->getMessage();
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
        $cartItemId = $_POST['cart_item_id'];
        $newQuantity = max(1, intval($_POST['quantity']));
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :cart_item_id AND user_id = :user_id");
        $stmt->execute(['quantity' => $newQuantity, 'cart_item_id' => $cartItemId, 'user_id' => $userId]);
        header("Location: cart.php");
        exit();
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart - CRAVINGS</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    min-height: 100vh;  

header {
    background-color: rgba(23, 23, 23);
    color: #fff;
    padding: 10px 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav h1 {
    margin: 0;
    font-size: 24px;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

nav ul li {
    margin-left: 20px;
}

nav a {
    color: #fff;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

nav a:hover {
    background-color: #575757;
}

.cart-container, .orders-container {
    flex: 1;  
}

.cart-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.cart-container h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
}

.cart-items {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.cart-item {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    width: 350px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f8f9fa;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s;
}

.cart-item img {
    width: 100px;
    height: auto;
    display: block;
    margin-bottom: 10px;
    border-radius: 5px;
}

.cart-item h4 {
    margin: 10px 0 5px;
    text-align: center;
    font-size: 18px;
}

.cart-item p {
    margin: 5px 0;
    text-align: center;
    font-size: 16px;
}

.quantity-form {
    text-align: center;
}

.quantity-form input[type="number"] {
    padding: 8px;
    width: 60px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.quantity-form button {
    padding: 8px 15px;
    margin-top: 10px;
    border-radius: 5px;
    border: none;
    background-color: #5cb85c;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

.quantity-form button:hover {
    background-color: #4cae4c;
}

.cart-summary {
    text-align: right;
    padding-top: 10px;
    font-size: 18px;
}

.place-order-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.place-order-btn:hover {
    background-color: #218838;
}

.orders-container {
    margin-top: 40px;
}

.orders-container h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
}

.orders-table {
    margin-top: 20px;
    border-collapse: collapse;
    width: 100%;
}

.orders-table th, .orders-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
    font-size: 16px;
}

.orders-table th {
    background-color: #f2f2f2;
}

footer {
    background-color: rgba(23, 23, 23);
    color: #fff;
    text-align: center;
    padding: 20px 0;
    width: 100%;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
}

footer ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

footer ul li {
    display: inline;
    margin-left: 15px;
}

footer ul li a {
    color: white;
    text-decoration: none;
    padding: 5px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

footer ul li a:hover {
    background-color: #575757;
}

    </style>
</head>
<body>
<header>
    <nav>
        <h1>CRAVINGS</h1>
        <ul>
            <li><a href="Homepage.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="cart.php">Cart</a></li>
        </ul>
    </nav>
</header>

<div class="cart-container">
    <h2>Your Cart</h2>
    <?php if ($cartItems): ?>
        <div class="cart-items">
            <?php 
            $total = 0;
            foreach ($cartItems as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                    <h4><?php echo htmlspecialchars($item['item_name']); ?></h4>
                    <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    <form action="cart.php" method="POST" class="quantity-form">
                        <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                        <label>Quantity:</label>
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <button type="submit" name="update_quantity">Update</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-summary">
            <p>Total: $<?php echo number_format($total, 2); ?></p>
            <form action="cart.php" method="POST">
                <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty. <a href="menu.php">Browse the menu</a>.</p>
    <?php endif; ?>
</div>

<div class="orders-container">
    <h2>Your Orders</h2>
    <?php if ($orders): ?>
        <table class="orders-table">
            <tr>
                <th>Order ID</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                    <td><?php echo number_format($order['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="no-orders" style="text-align: center;">No orders placed yet.</p>
    <?php endif; ?>
</div>

<footer>
    <ul>
        <li><a href="terms.php">Terms & Conditions</a></li>
        <li><a href="privacy.php">Privacy Policy</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
    <p>&copy; 2024 CRAVINGS. All rights reserved.</p>
</footer>

</body>
</html>
