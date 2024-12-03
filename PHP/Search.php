<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

$host = 'localhost';
$dbname = 'food_ordering';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = isset($_GET['query']) ? trim($_GET['query']) : '';

    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE item_name LIKE :query OR description LIKE :query");
    $stmt->execute(['query' => "%$query%"]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }
        .result-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: white;
        }
        .result-item h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>

<?php if ($results): ?>
    <?php foreach ($results as $item): ?>
        <div class="result-item">
            <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
            <p><?php echo htmlspecialchars($item['description']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>

<a href="Homepage.php">Go back to Home</a>

</body>
</html>
