<?php
session_start();

$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$defaultProfilePic = 'default.jpg';
$profilePic = $isLoggedIn && !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : $defaultProfilePic;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us - CRAVINGS</title>
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
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" style="width: 40px; height: 40px; border-radius: 50%;">
                </a>
                <span><a href="dashboard.php" style="color: #fff;"><?php echo htmlspecialchars($username); ?></a></span> 
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="User_Login.php">Sign In</a>
                <a href="User_Registration.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<section style="padding: 40px; text-align: center; background-color: #f8f8f8;">
    <h2>Contact Us</h2>
    <p>If you have any questions, comments, or feedback, please fill out the form below:</p>

    <form action="submit_contact.php" method="POST" style="max-width: 600px; margin: auto;">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" required style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc;" rows="5"></textarea><br>

        <button type="submit" style="padding: 10px 20px; background-color: #5cb85c; color: white; border-radius: 5px; border:none;">Send Message</button>
    </form>

    <h3>Contact Information</h3>
    <p>Email: sample@gmail.com</p>
    <p>Phone: 1234567890</p>
    <div class="map-container" style="margin-top: 20px;">
        <iframe src='https://www.google.com/maps/embed?pb=...' width='600' height='450' style="border: 0;"></iframe>
    </div>
</section>

<footer style="background-color: #171717; color: #fff; text-align: center; padding: 20px;">
    <div>
        <p>&copy; <?php echo date("Y"); ?> CRAVINGS. All Rights Reserved.</p>
        <ul style="list-style-type: none; padding: 0; margin: 10px 0;">
            <li><a href="privacy.php" style="color:#fff; text-decoration:none;">Privacy Policy</a></li>
            <li><a href="terms.php" style="color:#fff; text-decoration:none;">Terms of Service</a></li>
            <li><a href="contact.php" style="color:#fff; text-decoration:none;">Contact Us</a></li>
        </ul>
    </div>
</footer>

<script>
let mybutton = document.getElementById("myBtn");
window.onscroll = function() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
};
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>

</body> 
</html>
