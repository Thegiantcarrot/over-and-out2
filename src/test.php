<?php
// User-supplied data (username and password)
$username = $_POST['username']; // Replace with the actual way you get user input
$password = $_POST['password']; // Replace with the actual way you get user input

// Construct the SQLite query
$query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

// Execute the SQLite query using the sqlite3 command-line utility
$command = "sqlite3 w3s-dynamic-storage/database.db \"$query\"";
$output = shell_exec($command);

// Check if the query was successful
if ($output === null) {
    echo "Data inserted successfully.";
} else {
    echo "Error: $output";
}
?>
