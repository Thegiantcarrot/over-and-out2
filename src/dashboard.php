<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include 'db_connect.php';

// Fetch the user's account data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for updating account settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Validate and update the account settings in the database
    $errors = [];

    // Validate the username
    if (empty($new_username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
        $errors[] = "Username should only contain letters, numbers, and underscores.";
    } elseif ($new_username !== $user['username']) {
        // Check if the new username is different from the current username
        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $new_username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $errors[] = "Username already exists. Please choose a different username.";
        }
    }

    // Validate the email
    if (empty($new_email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($new_email !== $user['email']) {
        // Check if the new email is different from the current email
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $new_email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $errors[] = "Email already exists. Please choose a different email.";
        }
    }

    // If no validation errors, update the account settings
    if (empty($errors)) {
        $query = "UPDATE users SET username = :username, email = :email WHERE id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $new_username);
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Update the user's session data
        $_SESSION['username'] = $new_username;

        // Redirect to the dashboard with a success message
        header('Location: dashboard.php?success=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            padding: 10px;
            color: #fff;
        }

        nav {
            margin-top: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        .container {
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: inline-block;
            width: 100px;
        }

        .form-group input {
            width: 200px;
            padding: 5px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <nav>
            <ul>
                <li><a href="session.php">Session</a></li>
                <li><a href="review.php">Review</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Account Settings</h2>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
            <div class="success">
                Account settings updated successfully.
            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Account</button>
            </div>
        </form>
    </div>
</body>
</html>
