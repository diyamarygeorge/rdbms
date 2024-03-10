<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ag";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin@123") {
        header("Location: admin.php");
        exit();
    }

    $sql = "SELECT * FROM userdata WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username; // Store the username in the session
        header("Location: welcome.php");
        exit();
    }
    else{
        echo '<script>alert("Wrong username or password");</script>';
        
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora&display=swap">
    <style>
        /* Add your CSS styling here */
        body {
            font-family: 'Lora', serif;
            background-color: black;
            margin: 0;
            padding: 0;
            color: white;
        }

        form {
            width: 400px; 
            margin: 150px 700px;
            padding: 20px;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Added */
            z-index: 2; /* Added */
        }

        label {
            color: white;
            display: block;
            margin-bottom: 8px;
            border-radius: 25px;
        }

        input {
            width: 100%;
            border-radius: 25px;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: gold;
            color: black;
            border-radius: 25px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: darkgoldenrod;
        }

        p {
            color: red;
        }

        p.success {
            color: green;
        }
        .user-info {
            position: absolute;
            top: 10px;
            right: 20px;
            color: #333;
        }
        .left-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100vh;
            object-fit: cover;
            z-index: -1;
        }
        .black-background {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 20%;
            background: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0,0,1,1), rgba(0,0,1,1), rgba(0, 0, 0, 1));
            z-index: 0;
        }
        .log {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
            position: absolute;
            top: 23%;
            right: 26%; /* Adjust the value as needed */
            transform: translateY(-50%);
        }
        .log2 {
        font-size: 14px;
        font-weight: bold;
        color: white;
        position: absolute;
        top: 60%;
        right: 22%; /* Adjust the value as needed */
        transform: translateY(-50%);
        z-index: 3; /* Ensure link is clickable */
    }   
    </style>
</head>
<body>

    <img src="imgs/bg2.jpg" alt="Left Image" class="left-image">

    <div class="black-background"></div>
    

    
    <div class="log">LOGIN</div>
    
    <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <!-- Login form elements here -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
        <p id="loginError"></p> <!-- Placeholder for error message -->
    </form>
    <div class="log2"><a href="signup.php" style="color: gold;">Don't have an account?</a></div>


    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            if (username == "" || password == "") {
                alert("Username and password must be filled out");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>