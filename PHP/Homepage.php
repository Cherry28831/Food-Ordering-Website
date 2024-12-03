<?php
session_start();

$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$defaultProfilePic = 'default.jpg';
$profilePic = $isLoggedIn && !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : $defaultProfilePic;

$host = 'localhost';
$dbname = 'food_ordering';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $results = [];
    $allItems = []; 

    if ($query) {
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE item_name LIKE :query OR description LIKE :query");
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (empty($results)) {
        $stmt = $pdo->prepare("SELECT * FROM menu_items");
        $stmt->execute();
        $allItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $itemsByCategory = [];
    foreach ($allItems as $item) {
        $itemsByCategory[$item['category']][] = $item;
    }

    $categoryStmt = $pdo->prepare("SELECT DISTINCT category FROM menu_items");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CRAVINGS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<a href="cart.php" id="cartBtn" title="View Cart" style="display: none;">Cart</a>
<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<header>
    <nav>
        <div><h1>CRAVINGS</h1></div>
        <div class="nav-links">
            <ul>
                <li><a href="Homepage.php">Home</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </div>
        <div class="profile">
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php">
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                </a>
                <span><a href="dashboard.php" style="color:white;"><?php echo htmlspecialchars($username); ?></a></span> 
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="User_Login.php">Sign In</a>
                <a href="User_Registration.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div class="hero">
    <div class="slogan">Satisfy Your Cravings Anytime, Anywhere!</div>
    <div class="search-container">
        <form action="" method="GET" style="position: relative;">
            <input type="text" name="query" placeholder="Search for food items..." value="<?php echo htmlspecialchars($query); ?>" id="searchInput">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<section class="discounts" style="padding: 40px; text-align: center; background-color: rgba(23, 23, 23); color: white; min-height: 150px;">
    <h2 style="color: #fff;">Exclusive Discounts Just for You!</h2>
    <p style="font-size: 18px; color: #ccc;">Enjoy up to 20% off on your first order! Use code: <strong>CRAVINGS20</strong></p>
    <p style="font-size: 16px; color: #ccc;">Check our daily specials for more amazing deals!</p><br>
    <a href="#menu" style="padding: 10px 20px; background-color: #5cb85c; color: white; border-radius: 5px; text-decoration: none;">View Menu</a>
</section>

<div style="display: flex;">
<aside class="categories-sidebar">
    <h3 class="categories-title">Categories</h3>
    <div class="categories-container">
        <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <a href="#<?php echo urlencode($category); ?>" class="category-link">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</aside>

<section id="menu" style="flex-grow: 1; padding: 40px; text-align: center; background-color: #f8f8f8;">
    <h2 class="menu-heading">Our Menu</h2>

    <?php if ($query): ?>
        <h3>Search Results for "<?php echo htmlspecialchars($query); ?>"</h3>

        <?php if (!empty($results)): ?>
            <div class="menu-items-container">
                <?php foreach ($results as $item): ?>
                    <div class="menu-item-card" id="<?php echo urlencode($item['category']); ?>">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width:100%; height:auto; border-radius: 8px;">
                        <h4><?php echo htmlspecialchars($item['item_name']); ?></h4>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                        <button class="add-to-cart" data-item-id="<?php echo $item['id']; ?>">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($itemsByCategory as $category => $items): ?>
            <h3 id="<?php echo urlencode($category); ?>" style="margin-top: 20px; text-align: left;"><?php echo htmlspecialchars($category); ?></h3>
            <hr style="border: 1px solid #ccc; margin-bottom: 20px;">
            <div class="menu-items-container">
                <?php foreach ($items as $item): ?>
                    <div class="menu-item-card" id="<?php echo urlencode($item['category']); ?>">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width:100%; height:auto; border-radius: 8px;">
                        <h4><?php echo htmlspecialchars($item['item_name']); ?></h4>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                        <button class="add-to-cart" data-item-id="<?php echo $item['id']; ?>">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
</div>

<footer style="background-color: rgba(23, 23, 23); color: white; text-align: center; padding: 20px;">
    <div>
        <p>&copy; <?php echo date("Y"); ?> CRAVINGS. All Rights Reserved.</p>
        <ul style="list-style-type: none; padding: 0;">
            <li><a href="privacy.php" style="color:white; text-decoration:none;">Privacy Policy</a></li><br>
            <li><a href="terms.php" style="color:white; text-decoration:none;">Terms of Service</a></li><br>
            <li><a href="contact.php" style="color:white; text-decoration:none;">Contact Us</a></li>
        </ul>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let mybutton = document.getElementById("myBtn");
    let cartButton = document.getElementById("cartBtn");

    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
            cartButton.style.display = "block";
        } else {
            mybutton.style.display = "none";
            cartButton.style.display = "none";
        }
    };

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "item_id=" + itemId
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    alert("Item added to cart!");
                } else {
                    alert("Error adding item to cart.");
                }
            });
        });
    });
});

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>

</body>
</html>
