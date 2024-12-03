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

$login_success = '';
$login_errors = [];

if (isset($_SESSION['username'])) {
    header("Location: homepage.php");
    exit;
}

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    $username = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $login_success = "Login successful!";
            $_SESSION['username'] = $username;

            setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/");

            header("Location: homepage.php");
            exit;
        } else {
            $login_errors[] = "Incorrect password.";
        }
    } else {
        $login_errors[] = "No account found with that username.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('register_wallpaper.jpg');
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: inherit;
            background-size: cover;
            background-position: center;
            filter: blur(2px);
            z-index: -1;
        }

        .container {
            max-width: 350px;
            width: 100%;
            background: #232323;
            border: 1px solid #ddd;
            background-color: rgba(35, 35, 35, 0.85);
            padding: 30px 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h2 {
            color: white;
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin: 5px 0;
            background: #414141;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: white;
            font-size: 14px;
        }
        button {
            width: 40%;
            padding: 10px;
            background: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button:hover {
            background: #4cae4c;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            text-align: left;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #ddd;
        }
        .register-link a {
            color: #5cb85c;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        p {
            text-align: left;
            color: white;
            margin: 5px 0 0 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if ($login_success): ?>
        <div class="success"><?php echo $login_success; ?></div>
    <?php endif; ?>
    <?php if (!empty($login_errors)): ?>
        <div class="error">
            <h3>Errors:</h3>
            <?php foreach ($login_errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <p> Username: </p>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required> <br>
        <p> Password: </p>
        <input type="password" name="password" placeholder="Password" required> <br>
        <button type="submit" name="login">Login</button> <br>
    </form>
    <div class="register-link">
        Don't have an account? <a href="User_Registration.php">Register</a>
    </div>
</div>
</body>
</html>
