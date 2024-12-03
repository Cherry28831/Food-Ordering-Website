<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_ordering";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_item'])) {
        $id = $_POST['id'];
        $item_name = $_POST['item_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_POST['image'];

        $checkSql = "SELECT * FROM menu_items WHERE id=?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Item ID already exists. Please use a different ID.');</script>";
        } else {
            $sql = "INSERT INTO menu_items (id, item_name, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ississ", $id, $item_name, $description, $price, $category, $image);
            $stmt->execute();
            $stmt->close();
        }
        $checkStmt->close();
    } elseif (isset($_POST['update_item'])) {
        $id = $_POST['id'];
        $item_name = $_POST['item_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_POST['image'];

        $sql = "UPDATE menu_items SET item_name=?, description=?, price=?, category=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $item_name, $description, $price, $category, $image, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_item'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM menu_items WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

$orderSql = "SELECT * FROM orders";
$orderResult = $conn->query($orderSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
        }
        h2 {
            margin-top: 20px;
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        td {
            background-color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            margin: 5px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #007BFF;
            outline: none;
        }
    </style>
</head>
<body>

<h1>Menu Management</h1>

<div style="text-align: center;">
    <button class="button" id="addButton">Add Item</button>
    <button class="button" id="updateButton">Update Item</button>
    <button class="button" id="deleteButton">Delete Item</button>
    <button class="button" id="orderButton">View Orders</button>
</div>

<div id="formContainer" class="form-container"></div>

<div id="ordersContainer" class="form-container" style="display: none;">
    <h2>Orders</h2>
    <table id="ordersTable">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orderResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['price']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<table id="menuTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo htmlspecialchars($item['description']); ?></td>
                <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                <td><?php echo htmlspecialchars($item['category']); ?></td>
                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 50px; height: auto;"></td>
            </tr>-
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    document.getElementById('addButton').onclick = function() {
        document.getElementById('formContainer').innerHTML = `
            <h2>Add New Item</h2>
            <form method="POST">
                <input type="number" name="id" placeholder="Item ID" required>
                <input type="text" name="item_name" placeholder="Item Name" required>
                <input type="text" name="description" placeholder="Description" required>
                <input type="number" name="price" placeholder="Price" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="text" name="image" placeholder="Image URL" required>
                <button type="submit" name="add_item" class="button">Add Item</button>
            </form>
        `;
        document.getElementById('ordersContainer').style.display = 'none';
    };

    document.getElementById('updateButton').onclick = function() {
        document.getElementById('formContainer').innerHTML = `
            <h2>Update Item</h2>
            <form method="POST">
                <input type="number" name="id" placeholder="Item ID" required>
                <input type="text" name="item_name" placeholder="Item Name">
                <input type="text" name="description" placeholder="Description">
                <input type="number" name="price" placeholder="Price">
                <input type="text" name="category" placeholder="Category">
                <input type="text" name="image" placeholder="Image URL">
                <button type="submit" name="update_item" class="button">Update Item</button>
            </form>
        `;
        document.getElementById('ordersContainer').style.display = 'none';
    };

    document.getElementById('deleteButton').onclick = function() {
        document.getElementById('formContainer').innerHTML = `
            <h2>Delete Item</h2>
            <form method="POST">
                <input type="number" name="id" placeholder="Item ID" required>
                <button type="submit" name="delete_item" class="button">Delete Item</button>
            </form>
        `;
        document.getElementById('ordersContainer').style.display = 'none';
    };

    document.getElementById('orderButton').onclick = function() {
        document.getElementById('ordersContainer').style.display = 'block';
        document.getElementById('formContainer').innerHTML = '';
    };
</script>

</body>
</html>

<?php
$conn->close();
?>
