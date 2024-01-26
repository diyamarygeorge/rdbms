<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery</title>
    <style>
        /* Add your CSS styles here */
        .xyz {
            position: absolute;
            top: 100px;
            width:100%;  
            color: grey;
            text-align: center;
        }
        body {
            margin: 0;
            margin-bottom: 400px;
            padding: 0;
            background-image: linear-gradient(#D3D3D3, #000);
        }

        .black-bar {
            position: sticky;
            background-color: yellow;
            color: black;
            font-family: 'Blancha', sans-serif;
            padding: 10px;
            text-align: center;
        }

        .top-right {
            position: absolute;
            top: 10px;
            right: 30px;
            color: goldenrod;
            font-family: 'Arial', sans-serif;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 50px 20px 20px;
            position: relative; /* Set the container as relative for z-index */
        }

        .image-container img {
            width: 15%; /* Set images to 15% of the original width */
            height: auto; /* Maintain aspect ratio */
            border-radius: 20px;
            transition: transform 0.5s ease; /* Smooth transition on transform */
            position: relative; /* Set the image as relative for z-index */
            z-index: 1; /* Initially set z-index to 1 */
            cursor: pointer; /* Add cursor pointer to indicate clickability */
        }

        .profile-button {
            position: absolute;
            top: 50px;
            right: 20px;
            background-color: goldenrod;
            color: black;
            border-radius: 20px;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            font-family: 'Arial', sans-serif;
        }

        .logout-button {
            position: absolute;
            top: 50px;
            left: 20px; /* Position adjusted to the left */
            background-color: red;
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            text-decoration: none;
            font-family: 'Arial', sans-serif;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
            padding-top: 60px;
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }

        .modal img {
            width: 100%;
            height: auto;
        }

        .modal-text {
            color: white;
            text-align: center;
            padding: 14px 16px;
        }

        .close {
            color: white;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #ccc;
        }
    </style>
</head>
<body>

<div class="black-bar">
    <h1>ART GALLERY</h1>
    <div class="top-left">
        <?php
        session_start();
        // Display username if logged in
        if (isset($_SESSION['username'])) {
            echo '<a class="logout-button" href="login.php">Logout</a>';
        } else {
            echo '<a class="logout-button" href="login.php">Login</a>';
        }
        ?>
    </div>
</div>
<div class="xyz">
    <div class="top-left">
        <?php
        // Display username if logged in
        if (isset($_SESSION['username'])) {
            echo 'Logged in as: ' . $_SESSION['username'];
        } else {
            echo 'Login!!';
        }
        ?>
    </div>
</div>
<div class="image-container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ag";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve image paths, usernames, and descriptions from the database
    $sql = "SELECT username, description, path FROM ing";
    $result = $conn->query($sql);

    $imageData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageData[] = array(
                'username' => $row['username'],
                'description' => $row['description'],
                'path' => $row['path']
            );
        }
        // Shuffle the array to display images randomly
        shuffle($imageData);

        // Display images
        foreach ($imageData as $data) {
            echo '<img src="' . $data['path'] . '" alt="' . $data['description'] . '" onclick="openModal(\''
                . $data['username'] . '\', \'' . addslashes($data['description']) . '\', \''
                . $data['path'] . '\')">';
        }
    } else {
        echo "No images found.";
    }

    $conn->close();
    ?>
</div>

<a class="profile-button" href="upload.php">Profile</a>

<!-- Modal HTML -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Modal Image">
        <div class="modal-text">
            <p id="modalUsername"></p>
            <p id="modalDescription"></p>
        </div>
    </div>
</div>

<script>
    function openModal(username, description, imagePath) {
        document.getElementById("myModal").style.display = "block";
        document.getElementById("modalImage").src = imagePath;
        document.getElementById("modalUsername").innerHTML = "Uploaded by: " + username;
        document.getElementById("modalDescription").innerHTML = "Description: " + description;
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    const images = document.querySelectorAll('.image-container img');
    images.forEach(image => {
        image.addEventListener('mouseover', () => {
            images.forEach(otherImage => {
                if (otherImage !== image) {
                    otherImage.style.transform = 'scale(0.9)';
                    otherImage.style.zIndex = '0'; // Move shrinking images behind
                }
            });
            image.style.transform = 'scale(1)';
            image.style.zIndex = '1'; // Bring hovered image to the front
        });
        image.addEventListener('mouseout', () => {
            images.forEach(img => {
                img.style.transform = 'scale(1)';
                img.style.zIndex = '1'; // Reset z-index on mouseout
            });
        });
    });
</script>

</body>
</html>
