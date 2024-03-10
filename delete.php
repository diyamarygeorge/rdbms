<?php
session_start();

$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "ag";

// Establish a connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate the user's session and get their username
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        if (isset($_POST['deleteImages']) && is_array($_POST['deleteImages'])) {
            $deleteImages = $_POST['deleteImages'];
            $placeholders = rtrim(str_repeat('?, ', count($deleteImages)), ', ');

            // Delete entries associated with the user's username and the selected images
            $stmt = $conn->prepare("DELETE FROM ing WHERE username = ? AND path IN ($placeholders)");
            $stmt->bind_param("s" . str_repeat('s', count($deleteImages)), $username, ...$deleteImages);
            $stmt->execute();

            // Redirect to profile.php after deletion
            header("Location: upload.php");
            exit();
        }
    }

    // Retrieve images associated with the user
    $stmt = $conn->prepare("SELECT path FROM ing WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $imagePaths = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagePaths[] = $row['path'];
        }
    }

    $conn->close();
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Images</title>
    <style>
        /* Add your CSS styles here */
        body {
            margin: 0;
            padding: 0;
            background-image: url('imgs/bg4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: repeat;
            background-color: #000;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .black-bar {
            color: rgb(255, 255, 255);
            padding: 10px;
            text-align: center;
            color: black;
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

        .delete-button {
            text-align: center;
            margin-top: 20px;
        }

        .delete-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff0000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-link:hover {
            background-color: #cc0000;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .back-button a {
            color: black;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .back-button a:hover {
            color: #ff7700; /* Change color on hover if needed */
        }
    </style>
</head>
<body>

<div class="black-bar">
    <div class="back-button">
        <a href="upload.php">&#8592; Back</a>
    </div>
    <h1>Remove Images</h1>
</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete the selected images?');">
    <div class="image-container">
        <?php foreach ($imagePaths as $image): ?>
            <label>
                <input type="checkbox" name="deleteImages[]" value="<?php echo $image; ?>">
                <img src="<?php echo $image; ?>" alt="Image">
            </label>
        <?php endforeach; ?>
    </div>
    <div class="delete-button">
        <button type="submit" name="delete" class="delete-link">Delete</button>
    </div>
</form>

</body>
</html>
