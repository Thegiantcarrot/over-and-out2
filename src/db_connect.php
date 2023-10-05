<?php
$database_path = 'w3s-dynamic-storage/database.db';

// Create a new SQLite3 instance
$sqlite = new SQLite3($database_path);

// Check if the connection was successful
if (!$sqlite) {
    $error_message = $sqlite->lastErrorMsg();
    // Log the error to a file
    error_log("SQLite Error: $error_message", 0);
    die('Connection failed: ' . $error_message);
}
?>