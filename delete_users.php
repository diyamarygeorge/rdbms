<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Images</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color:white;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
        }

        button[type="submit"] {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 10px;
        }

        /* Popup styles */
        .popup {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
            z-index: 999;
        }

        .image-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .image-thumb {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .image-thumb.selected {
            border-color: #007bff;
        }

        .delete-btn {
            padding: 8px 15px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
        hr {
            border: 0;
            height: 1px;
            background-color: black;
            margin: 20px 0;
            z-index: 2;
        }
    </style>
    <script>
    // Function to handle image selection
    function toggleSelection(image) {
        image.classList.toggle('selected');
    }

    // Function to delete selected images
    function deleteImages() {
        var selectedImages = document.querySelectorAll('.image-thumb.selected');
        if (selectedImages.length === 0) {
            alert("Please select at least one image to delete.");
            return;
        }

        if (confirm("Are you sure you want to delete the selected images?")) {
            // Prepare an array to hold the image paths
            var imagePaths = [];
            selectedImages.forEach(function (image) {
                imagePaths.push(image.getAttribute('src'));
                image.parentNode.removeChild(image); // Remove from DOM
            });

            // Send the array of image paths to a PHP script for deletion
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'delete_images.php';
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'imagePaths';
            input.value = JSON.stringify(imagePaths);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

</head>
<body>

<h1>Delete User and Data</h1>

<a href="admin.php" class="back-btn">Back to Admin Dashboard</a>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ag";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userQuery = "SELECT Username FROM userdata";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows > 0) {
        echo '<label for="users">Select User:</label>';
        echo '<select name="userToDelete" id="users">';

        while ($row = $userResult->fetch_assoc()) {
            echo '<option value="' . $row['Username'] . '">' . $row['Username'] . '</option>';
        }

        echo '</select>';
    } else {
        echo "No users found";
    }

    $conn->close();
    ?>

    <button type="submit" name="deleteUser">Delete Selected User's Data</button>
</form>
<hr>
<h1>DELETE IMAGES</h1>
<div class="image-container">
    <?php
    // Establish connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch images
    $imagesQuery = "SELECT * FROM ing";
    $imagesResult = $conn->query($imagesQuery);

    // Display images as thumbnails
    if ($imagesResult->num_rows > 0) {
        while ($row = $imagesResult->fetch_assoc()) {
            echo '<img class="image-thumb" src="' . $row['path'] . '" alt="' . $row['title'] . '" onclick="toggleSelection(this)">';
        }
    } else {
        echo "No images found";
    }

    // Close connection
    $conn->close();
    ?>
</div>

<button class="delete-btn" onclick="deleteImages()">Delete Selected Images</button>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteUser'])) {
    $selectedUser = $_POST['userToDelete'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete images associated with the user
    $deleteImagesQuery = "DELETE FROM ing WHERE username = '$selectedUser'";
    if ($conn->query($deleteImagesQuery) === TRUE) {
        // Delete user data
        $deleteDataQuery = "DELETE FROM userdata WHERE Username = '$selectedUser'";
        if ($conn->query($deleteDataQuery) === TRUE) {
            echo "<div class='popup' id='successMsg'>User '$selectedUser' data deleted successfully.</div>";
            echo "<script>document.getElementById('successMsg').style.display = 'block'; closeMsg();</script>";
        } else {
            echo "Error deleting user's data: " . $conn->error;
        }
    } else {
        echo "Error deleting user's images: " . $conn->error;
    }

    $conn->close();
}
?>

</body>
</html>
