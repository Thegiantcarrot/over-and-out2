 <?php
    // Start the session
    session_start();

    // Check if the user is logged in, redirect to login page if not
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    // Database connection
    $servername = "localhost:3306";
    $usernameDB = "";
    $passwordDB = "";
    $dbname = "users";
    
    // Create connection
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $date = $_POST['date'];
        $ballsBowled = $_POST['balls_bowled'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        
        // Get the logged-in user's username
        $username = $_SESSION['username'];
        
        // Insert the session data into the database
        $sql = "INSERT INTO sessions (username, date, balls_bowled, rating, comment) VALUES ('$username', '$date', '$ballsBowled', '$rating', '$comment')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Session saved successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Session Form</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Session Form</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="balls_bowled">Balls Bowled:</label>
                <input type="number" id="balls_bowled" name="balls_bowled" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating:</label>
                <input type="number" id="rating" name="rating" required>
            </div>
            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment"></textarea>
            </div>
            <button type="submit">Save Session</button>
        </form>
    </div>
</body>
</html>
