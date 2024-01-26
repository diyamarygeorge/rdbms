<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Add your CSS styles here */
        /* Sample styling */
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://i.ibb.co/m0KS95F/background-image.jpg'); /* Updated image link */
            margin: 0;
            background-size: 25%;
        }

        .black-bar {
            background-color: rgba(255, 255, 0, 0.45);
            color: black;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
            font-family: 'Blancha', sans-serif;
            padding: 10px;
            text-align: center;
            backdrop-filter: blur(15px);
        }

        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 35px;
            border-radius: 20px;
            background-color: rgba(255, 255, 0, 0.45);
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(15px);
        }

        .back-to-home {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            border: none;
            border-radius: 50%;
            background-color: white;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .back-to-home:hover {
            background-color: #333;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #fffcc4;
        }

        .user-image {
            max-width: 50px;
            max-height: 50px;
        }

        .popup-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9); /* Adjust the alpha value for translucency */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup {
            background-color: rgba(255, 255, 255, 0.7); /* Adjust the alpha value for translucency */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .chart-container {
            width: 200px;
            height: 200px;
            margin: 0 auto; /* Center align horizontally */
            text-align: center; /* Center align content inside */
        }

        .chart-container canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
<div class="black-bar">
    <h1>Art Gallery</h1>
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
        $userDetailsQuery = "SELECT * FROM userdata";
        $userDetailsResult = $conn->query($userDetailsQuery);

        if ($userDetailsResult->num_rows > 0) {
            echo '<h3>User Details</h3>';
            echo '<table>';
            echo '<tr><th>Phone no</th><th>Username</th><th>Email</th></tr>';
            while ($userRow = $userDetailsResult->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $userRow['Phone no'] . '</td>';
                echo '<td>' . $userRow['Username'] . '</td>';
                echo '<td>' . $userRow['Email'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No user details available.</p>';
        }

        // Retrieve image paths and count for each user
        $imageCountQuery = "SELECT username, COUNT(path) AS image_count FROM ing GROUP BY username";
        $imageResult = $conn->query($imageCountQuery);

        if ($imageResult->num_rows > 0) {
            echo '<h3>Artworks Uploaded by Each User</h3>';
            echo '<table>';
            echo '<tr><th>Username</th><th>Artwork Uploaded</th><th>Artworks</th></tr>';
            while ($row = $imageResult->fetch_assoc()) {
                $username = $row['username'];
                $imageCount = $row['image_count'];

                echo '<tr>';
                echo '<td>' . $username . '</td>';
                echo '<td>' . $imageCount . '</td>';
                echo '<td>';

                // Retrieve images for the current user
                $userImagesQuery = "SELECT path FROM ing WHERE username = '$username'";
                $userImagesResult = $conn->query($userImagesQuery);

                while ($imageRow = $userImagesResult->fetch_assoc()) {
                    echo '<img class="user-image" src="' . $imageRow['path'] . '" alt="Image">';
                }

                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No Artworks uploaded yet.</p>';
        }

        $conn->close();
        ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="chart-container">
            <canvas id="userChart" width="200" height="200"></canvas>
        </div>
        <!-- ... (existing HTML) ... -->
        <button onclick="window.location.href = 'delete_users.php'">Delete Users</button>
        <!-- ... (existing HTML) ... -->

    </div>
</div>

</body>
</html>

<script>
    // Fetch data from the PHP backend
    fetch('getUserData.php') // Create a PHP file to handle this data retrieval
        .then(response => response.json())
        .then(data => {
            const registeredUsers = data.total_users;
            const usersWithImages = data.users_with_images;

            const ctx = document.getElementById('userChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'pie', // Change the chart type to pie
                data: {
                    labels: ['Registered Users', 'Users with Images'],
                    datasets: [{
                        label: 'User Count',
                        data: [registeredUsers, usersWithImages],
                        backgroundColor: [
                            'rgba(255, 255, 255, 1)',
                            'rgba(50, 50, 50, 01)'
                        ],
                        borderColor: [
                            'rgba(50, 50, 50, 1)',
                            'rgba(255,255,255, 1)'
                        ],
                        borderWidth: 2.5
                    }]
                },
                options: {
                    // You can add options specific to the pie chart here

                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
</script>
