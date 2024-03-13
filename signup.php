<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ag";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $check_username_sql = "SELECT * FROM userdata WHERE Username = '$username'";
    $check_username_result = $conn->query($check_username_sql);

    // Check if email already exists
    $check_email_sql = "SELECT * FROM userdata WHERE Email = '$email'";
    $check_email_result = $conn->query($check_email_sql);

    if ($check_username_result->num_rows > 0) {
         echo '<script>alert("Username taken");</script>';
    } elseif ($check_email_result->num_rows > 0) {
         echo '<script>alert("Email already exists");</script>';
    } else {
        $sql = "INSERT INTO userdata (Name, Email, `Phone no`, Username, Password) VALUES ('$name', '$email', '$phone', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login.php after successful sign-up
            echo '<script>alert("User created successfully. Please login."); window.location.href = "login.php";</script>';
            exit;
        
        
        } else {
            $signup_error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: gold;
            padding: 10px 0;
            text-align: center;
            font-size: 24px;
        }

        .content-wrapper {
            position: relative;
            z-index: 2;
            padding: 20px;
        }

        form {
            width: 400px;
            margin: 0 475px;
            padding: 20px;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            color: white;
            display: block;
            margin-bottom: 8px;
            border-radius: 25px;
        }

        input {
            width: calc(100% - 16px);
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

        .background-img {
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

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        .log2 {
        font-size: 14px;
        font-weight: bold;
        color: white;
        position: absolute;
        top: 85%;
        right: 18%; /* Adjust the value as needed */
        transform: translateY(-50%);
        z-index: 3; /* Ensure link is clickable */
    }   
    </style>
</head>
<body>

<img src="imgs/bg2.jpg" alt="Background" class="background-img">
<div class="black-background">  
    <h2 style="text-align: center;">Sign Up</h2>
    <form id="signupForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <!-- Sign up form elements here -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password" class="tooltip">Password:
            <span class="tooltiptext">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</span>
        </label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" name="signup" value="Sign Up">
    </form>
</div>
<div class="log2"><a href="login.php" style="color: gold;">Already an existing user?? Sign in</a></div>
<script>
    function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    // Regular expressions for email and phone number validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var phoneRegex = /^\d{10}$/;

    // Regular expression for password validation
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;

    // Regular expression for name validation (only alphabetic characters)
    var nameRegex = /^[A-Za-z]+$/;

    if (!name || !email || !phone || !username || !password) {
        alert("All fields are required");
        return false;
    }

    if (!nameRegex.test(name)) {
        alert("Name must consist of only alphabetic characters");
        return false;
    }

    if (!emailRegex.test(email)) {
        alert("Invalid email address");
        return false;
    }

    if (!phoneRegex.test(phone)) {
        alert("Invalid phone number (must be 10 digits)");
        return false;
    }

    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long and \ncontain at least one uppercase letter, \none lowercase letter, \none number, and \none special character");
        return false;
    }

    return true;
}

</script>
</body>
</html>
