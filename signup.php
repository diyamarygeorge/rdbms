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

    $sql = "INSERT INTO userdata (Name, Email, `Phone no`, Username, Password) VALUES ('$name', '$email', '$phone', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        $signup_success = "Registration successful. You can now log in.";

        // Redirect to login.php after successful sign-up
        header("Location: login.php");
        exit;
    } else {
        $signup_error = "Error: " . $sql . "<br>" . $conn->error;
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
    <style>
        /* Add your CSS styling here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('https://drive.google.com/uc?id=1-3JGsfOcDwYxARBaEeFzjzSg9CkLtrbl');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
        }

        header {
            background-color: yellow;
            padding: 10px 0;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #fff;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            color:black;
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            color: red;
        }

        p.success {
            color: green;
        }
    </style>
</head>
<body>
    <header>
        <h1>Art Gallery</h1>
    </header>
    <h2>Sign Up</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Sign up form elements here -->
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="signup" value="Sign Up">
    </form>
    <?php
    if (isset($signup_success)) echo "<p class='success'>$signup_success</p>";
    if (isset($signup_error)) echo "<p>$signup_error</p>";
    ?>
</body>
</html>
