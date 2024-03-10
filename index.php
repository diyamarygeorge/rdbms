<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <style>
        body {
            font-family: 'Lora', serif; /* Using Lora font */
            background: black;
            margin: 0;
            padding: 0;
            color: #fff;
            overflow-x: hidden;
            position: relative;
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

        table {
            position: absolute;
            margin-top: 300px;
            top: 50%;
            right: 12%;
            transform: translateY(-50%);
            z-index: 2;
        }

        td {
            padding: 10px;
        }

        .button {
            display: inline-block;
            text-align: center;
            width: 200px;
            text-decoration: none;
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: goldenrod;
        }

        .welcome-message {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }
    </style>
    <!-- Importing Lora font from Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora&display=swap">
</head>
<body>  
    <img src="imgs/bg2.jpg" alt="Left Image" class="left-image">

    <div class="black-background"></div>
    
    <!-- Table for Buttons -->
    <table>
        <!-- Welcome Message Row -->
        <tr>
            <td colspan="2" style="text-align: center;">
                <div class="welcome-message">Welcome to our Art Gallery</div>
            </td>
        </tr>
        <!-- Login Button Row -->
        <tr>
            <td><a class="button" href="login.php">Login</a></td>
        </tr>
        <!-- Sign Up Button Row -->
        <tr>
            <td><a class="button" href="signup.php">Sign Up</a></td>
        </tr>
    </table>
</body>
</html>
