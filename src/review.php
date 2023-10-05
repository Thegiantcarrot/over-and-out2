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

    // Get the logged-in user's username
    $username = $_SESSION['username'];

    // Query to get the last week's data
    $lastWeekQuery = "SELECT * FROM sessions WHERE username = '$username' AND date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
    $lastWeekResult = $conn->query($lastWeekQuery);

    // Query to get the ECB's recommended data for the user's age
    $ageQuery = "SELECT age FROM users WHERE username = '$username'";
    $ageResult = $conn->query($ageQuery);
    $age = 0; // Initialize age variable

    if ($ageResult->num_rows > 0) {
        $row = $ageResult->fetch_assoc();
        $age = $row['age'];
    }

    // Calculate the ECB's recommended values based on the age
    $ecbRecommendation = calculateECBRecommendation($age);

    // Variables to store the entered data
    $enteredDate = "";
    $enteredBallsBowled = 0;
    $enteredRating = 0;

    // Check if form data is submitted from session.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enteredDate = $_POST['date'];
        $enteredBallsBowled = $_POST['balls_bowled'];
        $enteredRating = $_POST['rating'];
    }

    // Function to calculate the ECB's recommended values based on age
    function calculateECBRecommendation($age) {
        // Placeholder function - replace with your own logic to calculate the ECB's recommendations based on age
        // Return an array of the recommended values
        return [
            'balls_bowled' => 100,
            'rating' => 5,
        ];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Review</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Include necessary JS libraries for chart generation -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Review</h2>
        <h3>Last Week's Data</h3>
        <?php
            // Check if there is last week's data available
            if ($lastWeekResult->num_rows > 0) {
                // Initialize arrays for chart data
                $lastWeekLabels = array();
                $lastWeekBallsBowled = array();
                $lastWeekRatings = array();

                // Iterate through each row of the result
                while ($row = $lastWeekResult->fetch_assoc()) {
                    $lastWeekLabels[] = $row['date'];
                    $lastWeekBallsBowled[] = $row['balls_bowled'];
                    $lastWeekRatings[] = $row['rating'];
                }

                // Generate the bar chart for last week's balls bowled
                echo '<canvas id="lastWeekBallsChart"></canvas>';
                echo '<script>';
                echo 'var lastWeekBallsCtx = document.getElementById("lastWeekBallsChart").getContext("2d");';
                echo 'var lastWeekBallsChart = new Chart(lastWeekBallsCtx, {';
                echo '    type: "bar",';
                echo '    data: {';
                echo '        labels: ' . json_encode($lastWeekLabels) . ',';
                echo '        datasets: [{';
                echo '            label: "Balls Bowled",';
                echo '            data: ' . json_encode($lastWeekBallsBowled) . ',';
                echo '            backgroundColor: "rgba(75, 192, 192, 0.2)",';
                echo '            borderColor: "rgba(75, 192, 192, 1)",';
                echo '            borderWidth: 1';
                echo '        }]';
                echo '    },';
                echo '    options: {';
                echo '        scales: {';
                echo '            y: {';
                echo '                beginAtZero: true';
                echo '            }';
                echo '        }';
                echo '    }';
                echo '});';
                echo '</script>';

                // Generate the bar chart for last week's ratings
                echo '<canvas id="lastWeekRatingsChart"></canvas>';
                echo '<script>';
                echo 'var lastWeekRatingsCtx = document.getElementById("lastWeekRatingsChart").getContext("2d");';
                echo 'var lastWeekRatingsChart = new Chart(lastWeekRatingsCtx, {';
                echo '    type: "bar",';
                echo '    data: {';
                echo '        labels: ' . json_encode($lastWeekLabels) . ',';
                echo '        datasets: [{';
                echo '            label: "Rating",';
                echo '            data: ' . json_encode($lastWeekRatings) . ',';
                echo '            backgroundColor: "rgba(255, 99, 132, 0.2)",';
                echo '            borderColor: "rgba(255, 99, 132, 1)",';
                echo '            borderWidth: 1';
                echo '        }]';
                echo '    },';
                echo '    options: {';
                echo '        scales: {';
                echo '            y: {';
                echo '                beginAtZero: true,';
                echo '                max: 10';
                echo '            }';
                echo '        }';
                echo '    }';
                echo '});';
                echo '</script>';
            } else {
                echo '<p>No data available for the last week.</p>';
            }
        ?>
        <h3>Today's Data</h3>
        <?php
            // Check if form data is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Generate the bar chart for today's data and ECB recommendations
                echo '<canvas id="todayDataChart"></canvas>';
                echo '<script>';
                echo 'var todayDataCtx = document.getElementById("todayDataChart").getContext("2d");';
                echo 'var todayDataChart = new Chart(todayDataCtx, {';
                echo '    type: "bar",';
                echo '    data: {';
                echo '        labels: ["Balls Bowled", "Rating"],';
                echo '        datasets: [{';
                echo '            label: "Your Data",';
                echo '            data: [' . $enteredBallsBowled . ', ' . $enteredRating . '],';
                echo '            backgroundColor: "rgba(54, 162, 235, 0.2)",';
                echo '            borderColor: "rgba(54, 162, 235, 1)",';
                echo '            borderWidth: 1';
                echo '        }, {';
                echo '            label: "ECB Recommendations",';
                echo '            data: [' . $ecbRecommendation['balls_bowled'] . ', ' . $ecbRecommendation['rating'] . '],';
                echo '            backgroundColor: "rgba(255, 206, 86, 0.2)",';
                echo '            borderColor: "rgba(255, 206, 86, 1)",';
                echo '            borderWidth: 1';
                echo '        }]';
                echo '    },';
                echo '    options: {';
                echo '        scales: {';
                echo '            y: {';
                echo '                beginAtZero: true,';
                echo '                max: 10';
                echo '            }';
                echo '        }';
                echo '    }';
                echo '});';
                echo '</script>';
            } else {
                echo '<p>No data available for today.</p>';
            }
        ?>
        <a href="session.php" class="btn">Go back to Session Form</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
