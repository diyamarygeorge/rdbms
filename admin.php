<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            color: white;
            margin: 0;
        }

        .black-bar {
            background-color: #34495e;
            padding: 10px 0;
            text-align: center;
        }

        .black-bar h1 {
            margin: 0;
        }

        .back-to-home {
            position: absolute;
            top: 10px;
            left: 10px;
            color: white;
            font-size: 24px;
            text-decoration: none;
        }

        .dashboard-container {
            background-color: #2c3e50;
        }

        .dashboard-container h1 {
            color: #ecf0f1;
        }

        .user-info {
            background-color: #34495e;
            padding: 20px;
            margin-top: 20px;
        }

        .user-info h3 {
            color: #ecf0f1;
        }

        .user-info p {
            color: #ecf0f1;
        }

        table {
            width: 100%;
            color: white;
        }

        th, td {
            border: 1px solid #ecf0f1;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #34495e;
        }

        tr:nth-child(even) {
            background-color: #2c3e50;
        }

        .image-container {
            display: table-cell;
            vertical-align: top;
            padding: 10px 0;
        }

        .image-container img {
            width: 75px;
            height: 75px;
            object-fit: cover;
            margin-right: 5px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            max-height: 90vh;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 75vh;
            max-height: 80vh; /* 80% of the viewport height */
        }

        .modal-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Maintain aspect ratio */
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        .modal-body {
            padding: 2px 16px;
            color: white;
        }
        .delete-button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
   
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
        .size{
            height:50vh;
            width:50vh;
        }

       
    </style>
</head>
<body>
<div class="black-bar">
    <h1>Art Gallery</h1>
    <button class="delete-button" onclick="window.location.href = 'delete_users.php';">Delete Users/Photos</button>
</div>
<a href="index.php" class="back-to-home">&#127968;</a>
<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="user-info">
        <h3>Total Users and Images</h3>
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

        // Count distinct users
        $userCountQuery = "SELECT COUNT(DISTINCT Username) AS total_users FROM userdata";
        $userResult = $conn->query($userCountQuery);
        $userData = $userResult->fetch_assoc();

        // Count total images uploaded
        $totalImagesQuery = "SELECT COUNT(path) AS total_images FROM ing";
        $totalImagesResult = $conn->query($totalImagesQuery);
        $totalImagesData = $totalImagesResult->fetch_assoc();

        echo '<p>Total Users: ' . $userData['total_users'] . '</p>';
        echo '<p>Total Images: ' . $totalImagesData['total_images'] . '</p>';

        // Retrieve user details from the "userdata" table
        $userDetailsQuery = "SELECT u.*, COUNT(i.path) AS total_photos FROM userdata u LEFT JOIN ing i ON u.Username = i.Username GROUP BY u.Username";

// Fetch all user details including additional columns
$userDetailsQuery = "SELECT u.*, COUNT(i.path) AS total_photos 
                     FROM userdata u 
                     LEFT JOIN ing i ON u.Username = i.Username 
                     GROUP BY u.Username";

$userDetailsResult = $conn->query($userDetailsQuery);

if ($userDetailsResult->num_rows > 0) {
    echo '<h3>User Details</h3>';
    echo '<table>';
    echo '<tr><th>User ID</th><th>Username</th><th>Password</th><th>Email</th><th>Phone No</th><th>Name</th><th>Total Photos</th><th>Images</th></tr>';
    while ($userRow = $userDetailsResult->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $userRow['user_id'] . '</td>';
        echo '<td>' . $userRow['Username'] . '</td>';
        echo '<td>' . $userRow['Password'] . '</td>';
        echo '<td>' . $userRow['Email'] . '</td>';
        echo '<td>' . $userRow['Phone no'] . '</td>';
        echo '<td>' . $userRow['Name'] . '</td>';
        echo '<td>' . $userRow['total_photos'] . '</td>';
        echo '<td class="image-container">';
        // Retrieve images for this user
        $userImagesQuery = "SELECT * FROM ing WHERE Username = '{$userRow['Username']}'";
        $userImagesResult = $conn->query($userImagesQuery);
        while ($imageRow = $userImagesResult->fetch_assoc()) {
            echo '<img src="' . $imageRow['path'] . '" alt="Image" onclick="showImageDetails(\'' . $imageRow['path'] . '\', \'' . $imageRow['title'] . '\', \'' . $imageRow['description'] . '\', \'' . $imageRow['category'] . '\', \'' . $imageRow['orientation'] . '\', \'' . $imageRow['uploaded_at'] . '\')">';
        }
        echo '</td>'; // Close image-container
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No user details available.</p>';
}


        // Fetch data from the database for the pie chart
        $categoryCountQuery = "SELECT category, COUNT(*) AS count FROM ing GROUP BY category";
        $categoryCountResult = $conn->query($categoryCountQuery);

        $categoryLabels = [];
        $categoryCounts = [];
        if ($categoryCountResult->num_rows > 0) {
            while ($row = $categoryCountResult->fetch_assoc()) {
                $categoryLabels[] = $row['category'];
                $categoryCounts[] = $row['count'];
            }
        }

        $conn->close();
        ?>
    </div>
</div>

<!-- The Modal -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="expandedImage" src="" alt="Expanded Image">
        <div class="modal-body" id="imageDetails"></div>
    </div>
</div>
<div class="size">
    <h1>GRAPHS</h1>
    <!-- Add canvas for pie chart -->
    <!-- Add canvas for pie chart with 50% of screen size -->
    <canvas id="pieChart" style="width: 50vw; height: 50vh;"></canvas>
</div>


<script>
    function showImageDetails(path, title, description, category, orientation, uploaded_at) {
        const modal = document.getElementById("imageModal");
        const expandedImage = document.getElementById("expandedImage");
        const imageDetails = document.getElementById("imageDetails");
        
        expandedImage.src = path;
        imageDetails.innerHTML = "<strong>Title: </strong>" + title + "<br><strong>Description: </strong>" + description + "<br><strong>Category: </strong>" + category + "<br><strong>Orientation: </strong>" + (orientation === '1' ? 'Portrait' : 'Landscape') + "<br><strong>Uploaded At: </strong>" + uploaded_at;

        modal.style.display = "block";
    }

    function closeModal() {
        const modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }
    
    // Get data for the pie chart
    const pieChartData = {
        labels: <?php echo json_encode($categoryLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($categoryCounts); ?>,
            backgroundColor: [
                '#ff6384',
                '#36a2eb',
                '#ffce56',
                '#4bc0c0',
                '#9966ff',
                '#ff9966'
            ]
        }]
    };

    // Draw the pie chart
    const pieChartCanvas = document.getElementById('pieChart').getContext('2d');
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true,
            
        }
    });
</script>
</body>
</html>
