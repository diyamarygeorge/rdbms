<?php
// delete_images.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imagePaths'])) {
    $imagePaths = json_decode($_POST['imagePaths']);

    // Establish connection to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ag";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete images and associated data from the database
    foreach ($imagePaths as $imagePath) {
        // Delete image from the 'ing' table
        $deleteQuery = "DELETE FROM ing WHERE path = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $imagePath);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();

    // Redirect back to delete_images.php
    header("Location: delete_users.php");
    exit();
}
?>
