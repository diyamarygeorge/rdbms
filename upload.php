<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <style>
        body {
    font-family: Arial, sans-serif;
    background-image: url('imgs/bg3.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: repeat;
    background-color: #000;
    color: #fff;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

h1 {
    color: #000000;
    margin-bottom: 20px;
}

.top-right {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: black;
    padding: 10px;
    border-radius: 20px;
}

.top-right a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.top-right a:hover {
    color: rgb(255, 119, 0);
}

.top-left {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: black;
    padding: 10px;
    border-radius: 20px;
}

.image-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 20px;
    justify-content: center;
}

.image-container img {
    width: 150px;
    height: auto;
    border-radius: 20px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.image-container img:hover {
    transform: scale(1.1);
}

.button-container {
    margin-top: 20px;
}

.delete-button,
.upload-button {
    background-color: #ff0000;
    color: #fff;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-right: 10px;
}

.delete-button:hover,
.upload-button:hover {
    background-color: #cc0000;
}
    </style>
</head>
<body>

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

<div class="top-right">
    <a href="welcome.php">Home</a>
</div>

<h1>PROFILE PAGE</h1>

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

<div class="button-container">
    <a class="delete-button" href="delete.php">Delete Artwork</a>
    <a class="upload-button" href="upload2.php">Upload</a>
</div>

</body>
</html>
