<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ag';

// Create a database connection
$connection = new mysqli($host, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Check if the user is logged in
    session_start();
    if (!isset($_SESSION['username'])) {
        echo "Error: User not logged in.";
        exit;
    }

    $username = $_SESSION['username'];
    $description = $_POST['description'];

    // Use an absolute path for the target directory
    $targetDirectory = "uploads/";

    // Ensure the target directory exists
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0755, true);
    }

    // Upload image to the server
    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

    // Insert data into the "ing" table
    $sql = "INSERT INTO ing (username, path, description) VALUES ('$username', '$targetFile', '$description')";
    if ($connection->query($sql) === TRUE) {
        echo "Image uploaded and data inserted successfully.";
        
        // Redirect to upload.php after successful upload
        header("Location: upload.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>

    <style>
        .xyz {
            position: absolute;
            top: 100px;
            width: 100%;  
            color: grey;
            text-align: center;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-bottom: 24px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
    <label for="description">Description:</label>
    <input type="text" name="description" required>

    <label for="image">Select Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <input type="submit" value="Upload">
</form>

</body>
</html>
