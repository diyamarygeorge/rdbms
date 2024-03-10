<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
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

    // Initialize message variable
    $message = "";

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
        // Check if the user is logged in
        session_start();
        if (!isset($_SESSION['username'])) {
            $message = "Error: User not logged in.";
        } else {
            $username = $_SESSION['username'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $orientation = $_POST['orientation'];

            // Use an absolute path for the target directory
            $targetDirectory = "uploads/";

            // Ensure the target directory exists
            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }

            // Upload image to the server
            $targetFile = $targetDirectory . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Determine image orientation
                $imageOrientation = (int)$orientation; // Convert to integer

                // Insert data into the "ing" table
                $sql = "INSERT INTO ing (title, category, username, path, description, orientation, uploaded_at) VALUES ('$title', '$category', '$username', '$targetFile', '$description', $imageOrientation, NOW())";
                if ($connection->query($sql) === TRUE) {
                    $message = "UPLOAD SUCCESSFUL";
                    echo "<script>alert('" . $message . "');</script>";
                    // Redirect to upload.php after successful upload
                    echo "<script>window.location.href = 'upload.php';</script>";
                    exit;
                } else {
                    $message = "Error: " . $sql . "<br>" . $connection->error;
                }
            } else {
                // Set error message
                $message = "Error uploading the image.";
            }
        }
    }
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('imgs/bg1.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        select,
        input[type="file"] {
            width: calc(100% - 16px); /* Subtract padding */
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.5); /* Translucent white background */
        }

        input[type="file"] {
            width: calc(100% - 16px); /* Subtract padding */
            padding: 8px;
            margin-bottom: 24px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="radio"] {
            margin-right: 8px;
        }

        input[type="submit"] {
            background-color: #024ac1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
        }

        td {
            padding: 8px;
            vertical-align: top;
        }

        .button-container {
            text-align: center;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
        #upload-heading {
            font-size: 24px;
            font-family: cursive;
            letter-spacing: 2px;
            font-weight: bold;
            color: #00008B; /* Dark blue color */
            margin-bottom: 20px;
        }

        #back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            color: blue;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<a id="back-button" href="upload.php">Back</a>

<form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
    <table>
        <tr><h1 id="upload-heading">Upload Images</h1></tr>
        <tr>
            <td><label for="title">Title:</label></td>
            <td><input type="text" id="title" name="title" required></td>
        </tr>
        <tr>
            <td><label for="description">Description:</label></td>
            <td><input type="text" id="description" name="description" required></td>
        </tr>
        <tr>
            <td><label for="category">Category:</label></td>
            <td>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Realism">Realism</option>
                    <option value="Impressionism">Impressionism</option>
                    <option value="Abstract Art">Abstract Art</option>
                    <option value="Surrealism">Surrealism</option>
                    <option value="Expressionism">Expressionism</option>
                    <option value="Cubism">Cubism</option>
                    <option value="Minimalism">Minimalism</option>
                    <option value="Pop Art">Pop Art</option>
                    <option value="Renaissance Art">Renaissance Art</option>
                    <option value="Baroque Art">Baroque Art</option>
                    <option value="Modern Art">Modern Art</option>
                    <option value="Contemporary Art">Contemporary Art</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="orientation">Orientation:</label></td>
            <td>
                <input type="radio" id="portrait" name="orientation" value="1" required>  Portrait
                <input type="radio" id="landscape" name="orientation" value="0"> Landscape
            </td>
        </tr>
        <tr>
            <td><label for="image">Select Image:</label></td>
            <td><input type="file" id="image" name="image" accept="image/*" required></td>
        </tr>
        <tr>
            <td colspan="2" class="button-container"><input type="submit" value="Upload"></td>
        </tr>
    </table>
    <div id="error-message" class="error-message"><?php echo $message; ?></div>
</form>

<script>
    // Function to validate form
    function validateForm() {
        var title = document.getElementById('title').value;
        var description = document.getElementById('description').value;
        var category = document.getElementById('category').value;
        var portrait = document.getElementById('portrait').checked;
        var landscape = document.getElementById('landscape').checked;
        var image = document.getElementById('image').files[0];

        // Check if any field is empty
        if (title.trim() === '' || description.trim() === '' || category === '' || (!portrait && !landscape) || !image) {
            document.getElementById('error-message').innerText = 'Please fill all fields and select an image.';
            return false;
        }

        // Check image size and format
        var allowedFormats = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedFormats.includes(image.type) || image.size > 5 * 1024 * 1024) {
            document.getElementById('error-message').innerText = 'Please select a JPEG, JPG, or PNG image file below 5MB.';
            return false;
        }

        return true;
    }
</script>
</body>
</html>
