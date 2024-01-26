<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <style>
        /* Add your CSS styling here */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    text-align: center;
    background-image: url('https://drive.google.com/uc?id=1-3JGsfOcDwYxARBaEeFzjzSg9CkLtrbl');
    background-size: cover;
    background-position: bottom;
    background-repeat: repeat;
    color: #fff; /* Set text color to white or another color that contrasts well with the background */
}

header {
    background-color: yellow;
    padding: 10px 0;
}

h1 {
    color: #333;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: inline-block;
    margin: 10px;
}

a {
    text-decoration: none;
    background-color: #4caf50;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #45a049;
}


    </style>
</head>
<body>
    <header>
        <h1>Art Gallery</h1>
    </header>
    <h2>Select an Option</h2>
    <ul>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <button onclick="window.location.href = 'admin.php'" style="position: fixed; bottom: 20px; right: 20px;">Go to Admin Page</button>

    </ul>
</body>
</html>
