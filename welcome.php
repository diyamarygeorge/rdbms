<?php
session_start(); // Place this at the very beginning of your PHP code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery</title>
    <style>
        /* Add your CSS styles here */
        .xyz {
            position: absolute;
            top: 10px;
            width:100%;  
            color: grey;
            text-align: center;
        }
        body {
            margin: 0;
            padding: 0;
            background-image:  url('imgs/bg5.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color:black;
        }

        .black-bar {
            position: sticky;
            background-color: rgba(0, 0, 0, 0.7); /* Adjust opacity using rgba */
            color: white;
            font-family: 'Blackadder'; /* Change font family */
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

        .image-container img:hover {
            transform: scale(1.1);
            z-index:3;
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
            background-color: rgba(0,0,0,0.9);
            padding-top: 60px;
        }

        .modal-content {
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 90%;
            max-height: 90%;
            overflow: hidden;
        }

        .modal img {
            max-width: 50%;
            max-height: 50%;
            object-fit: contain; /* Ensure the image retains its aspect ratio */
            display: block;
            margin: auto;
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

        .filter-section {
            margin-top: 20px;
            text-align: right;
        }

        .filter-section label {
            
            font-family: 'Arial', sans-serif;
            color:white;
        }

        .filter-section select {
            padding: 5px;
            border-radius: 5px;
            font-family: 'Arial', sans-serif;
        }
        
    </style>
</head>
<body>

<div class="black-bar">
    <h1>ART GALLERY</h1>
    <div class="top-left">
        <?php

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

<div class="filter-section">
    <label for="orientation-filter">Filters:</label>
    <select id="orientation-filter" onchange="filterImages()">
        <option value="all">All Orientations</option>
        <option value="0">Landscape</option>
        <option value="1">Portrait</option>
    </select>

 
    <select id="category-filter" onchange="filterImages()">
    <option value="all">All Categories</option>
                    <option value="Realism">Realism</option>
                    <option value="Impressionism">Impressionism</option>
                    <option value="Abstract Art">Abstract Art</option>
                    <option value="Surrealism">Surrealism</option>
                    <option value="Expression">Expressionism</option>
                    <option value="Cubism">Cubism</option>
                    <option value="Minimalism">Minimalism</option>
                    <option value="Pop Art">Pop Art</option>
                    <option value="Renaissanc">Renaissance Art</option>
                    <option value="Baroque Ar">Baroque Art</option>
                    <option value="Modern Art">Modern Art</option>
                    <option value="Contempora">Contemporary Art</option>
    </select>
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

    // Retrieve image paths, usernames, descriptions from the database
    $sql = "SELECT username, description, path, orientation, category, title, uploaded_at FROM ing";

    $result = $conn->query($sql);

    $imageData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageData[] = array(
                'username' => $row['username'],
                'description' => $row['description'],
                'path' => $row['path'],
                'orientation' => $row['orientation'],
                'category' => $row['category'],
                'title' => $row['title'],
                'uploaded_at' => $row['uploaded_at']
            );
        }
        
        // Shuffle the array to display images randomly
        shuffle($imageData);

        // Display images
        foreach ($imageData as $data) {
            // Add style attribute to maintain aspect ratio
            echo '<img src="' . $data['path'] . '" alt="' . $data['description'] . '" data-username="' . $data['username'] . '" data-description="' . addslashes($data['description']) . '" data-details=\'' . json_encode($data) . '\' data-category="' . $data['category'] . '" data-orientation="' . $data['orientation'] . '" onclick="openModal(this)" style="object-fit: contain;">';
        }
        
        
    } else {
        echo "No images found.";
    }
    

    $conn->close();
    ?>
</div>

<a class="profile-button" href="upload.php">Profile</a>

<!-- Modal HTML -->
<!-- Modal HTML -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Modal Image">
        <div class="modal-text">
            <p id="modalUsername"></p>
            <p id="modalDescription"></p>
            <p id="modalDetails"></p> <!-- Add a new paragraph for additional details -->
        </div>
    </div>
</div>
<script>
    function openModal(image) {
        const username = image.getAttribute('data-username');
        const description = image.getAttribute('data-description');
        const imagePath = image.src;
        const details = JSON.parse(image.getAttribute('data-details')); // Parse additional details

        // Define orientation text based on the orientation value
        const orientationText = details.orientation === '1' ? 'Portrait' : 'Landscape';

        document.getElementById("myModal").style.display = "block";
        document.getElementById("modalImage").src = imagePath;
        document.getElementById("modalUsername").innerHTML = "Uploaded by: " + username;
        document.getElementById("modalDescription").innerHTML = "Description: " + description;
        document.getElementById("modalDetails").innerHTML = "Title: " + details.title + "<br>" +
            "Uploaded At: " + details.uploaded_at + "<br>" +
            "Category: " + details.category + "<br>" +
            "Orientation: " + orientationText; // Use orientation text
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    function filterImages() {
        const orientationFilter = document.getElementById('orientation-filter').value;
        const categoryFilter = document.getElementById('category-filter').value;

        console.log("Orientation Filter:", orientationFilter);
        console.log("Category Filter:", categoryFilter);

        const images = document.querySelectorAll('.image-container img');

        images.forEach(image => {
            const orientation = image.getAttribute('data-orientation');
            const category = image.getAttribute('data-category');

            console.log("Image Orientation:", orientation);
            console.log("Image Category:", category);

            if ((orientationFilter === 'all' || orientation === orientationFilter) &&
                (categoryFilter === 'all' || category === categoryFilter || (category === undefined && categoryFilter === 'other'))) {
                image.style.display = 'block';
            } else {
                image.style.display = 'none';
            }
        });
    }
</script>


</body>
</html>
