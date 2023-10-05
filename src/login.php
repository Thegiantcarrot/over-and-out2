<?php
// Include the database connection file
include 'db_connect.php';

// Initialize variables
$username = $password = '';
$loginError = '';

// User login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the entered password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, perform login action
            echo "Login successful!";
            // You can redirect the user to another page here if needed.
        } else {
            $loginError = "Invalid password!";
        }
    } else {
        $loginError = "User not found!";
    }
    $stmt->close();
}

$conn->close();
?>

<!-- Include your HTML template in a separate file (e.g., login_form.php) -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="" method="post">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username); ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
        <span class="error"><?php echo $loginError; ?></span>
    </form>
</body>
</html>
