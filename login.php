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

    $sql = "SELECT * FROM userdata WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username; // Store the username in the session
        header("Location: welcome.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
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
    <style>
        /* Add your CSS styling here */
        // ... (your existing CSS)
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
            color: black;
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
        .user-info {
            position: absolute;
            top: 10px;
            right: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Art Gallery</h1>
    </header>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Login form elements here -->
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
        <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
    </form>
        <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
    </form>
</body>
</html>
