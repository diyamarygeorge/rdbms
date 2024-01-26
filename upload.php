<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

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
            flex-direction: column;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 50px 20px 20px;
        }

        .image-container img {
            width: 15%;
            height: auto;
            border-radius: 20px;
            transition: transform 0.5s ease;
            position: relative;
            z-index: 1;
        }

        .delete-button,
        .upload-button {
            background-color: red;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="xyz">
    <div class="top-left">
        <?php
        session_start();
        // Display username if logged in
        if (isset($_SESSION['username'])) {
            echo 'Logged in as: ' . $_SESSION['username'];
        } else {
            echo 'Login!!';
        }
        ?>
    </div>
</div>

<h1>Profile</h1>

<div class="image-container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ag";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Retrieve image paths for the logged-in user
    if (isset($_SESSION['username'])) {
        $loggedInUsername = $_SESSION['username'];
        $sql = "SELECT path FROM ing WHERE username = '$loggedInUsername'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<img src="' . $row['path'] . '" alt="Image">';
            }
        } else {
            echo "No images found for the logged-in user.";
        }
    } else {
        echo "Login to view your profile.";
    }

    $connection->close();
    ?>
</div>

<a class="delete-button" href="delete.php">Delete Artwork</a>
<a class="upload-button" href="upload2.php">Upload</a>

</body>
</html>



