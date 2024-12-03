<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "food_ordering";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$registration_success = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_username = trim($_POST['reg_username']);
    $reg_password = password_hash(trim($_POST['reg_password']), PASSWORD_BCRYPT);
    $reg_email = trim($_POST['reg_email']);
    
    $usernameP = "/^[a-zA-Z0-9]{2,50}$/"; 
    $emailP = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"; 

    if (!preg_match($usernameP, $reg_username)) {
        $errors[] = "Username should have 2 to 50 characters and no special characters.";
    }
    if (!preg_match($emailP, $reg_email)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($reg_password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $reg_username, $reg_password, $reg_email);

        if ($stmt->execute()) {
            $registration_success = "Registration successful!";
            header("Location: User_Login.php");
            exit;
        } else {
            $errors[] = "Error: " . $stmt->error;
        }  
    }          
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
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
            padding: 30px 20px;
            background-color: rgba(35, 35, 35, 0.85);
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

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            color: #ddd;
        }
        .login-link a {
            color: #5cb85c;
            text-decoration: none;
        }

        .login-link a:hover {
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
    <h2>Register</h2>
    <?php if ($registration_success): ?>
        <div class="success"><?php echo $registration_success; ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <h3>Errors:</h3>
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <p> Username: </p>
        <input type="text" name="reg_username" placeholder="Username" required> <br>
        <p> Password: </p>
        <input type="password" name="reg_password" placeholder="Password" required> <br>
        <p> Email: </p>
        <input type="email" name="reg_email" placeholder="Email" required> <br>
        <button type="submit" name="register">Register</button> <br>
    </form>
    <div class="login-link">
        Already have an account? <a href="User_Login.php">Log in</a>
    </div>
</div>
</body>
</html>
