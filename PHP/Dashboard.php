<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_ordering";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header('Location: User_Login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $updateQuery = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ssssi', $name, $email, $phone, $address, $user_id);
    
    if ($updateStmt->execute()) {
        $success = "Profile updated successfully!";
        $user['username'] = $name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $error = "Error updating profile.";
    }
}

$orderQuery = "SELECT * FROM orders WHERE user_id = ?";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param('i', $user_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

$addressQuery = "SELECT * FROM addresses WHERE user_id = ?";
$addressStmt = $conn->prepare($addressQuery);
$addressStmt->bind_param('i', $user_id);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - CRAVINGS</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; margin: 0 auto; padding: 20px; }
        h2 { color: #333; }
        .profile-form, .order-history, .saved-addresses { background: #fff; padding: 20px; margin-top: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; margin-top: 5px; }
        .button { margin-top: 10px; padding: 10px 15px; background-color: #333; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .button:hover { background-color: #575757; }
        .success { color: green; }
        .error { color: red; }
        .order-item, .address-item { padding: 10px 0; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Profile</h2>
    
    <div class="profile-form">
        <h3>Update Profile</h3>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label for="address">Address</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

            <button type="submit" class="button">Update Profile</button>
        </form>
    </div>

    <div class="order-history">
        <h3>Order History</h3>
        <?php while ($order = $orderResult->fetch_assoc()): ?>
            <div class="order-item">
                <p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
                <p>Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                <p>Total: $<?php echo htmlspecialchars($order['total_amount']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="saved-addresses">
        <h3>Saved Addresses</h3>
        <?php while ($address = $addressResult->fetch_assoc()): ?>
            <div class="address-item">
                <p><?php echo htmlspecialchars($address['address']); ?></p>
                <p><?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['zip']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
