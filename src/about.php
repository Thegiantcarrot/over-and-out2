 <!DOCTYPE html>
<html>
<head>
    <title>Over and Out - About</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        .content {
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        .copywrite {
            font-size: 12px;
            text-align: center;
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>

    <div class="content">
        <h1>About Over and Out</h1>
        <p>Over and Out is a training tracking website designed to help individuals optimize their training routines and improve their performance.</p>
        <p>By logging their training sessions and analyzing the data, users can gain valuable insights into their progress, identify areas for improvement, and receive recommendations tailored to their goals.</p>
        <p>At Over and Out, we are committed to providing a user-friendly and intuitive platform that empowers athletes and fitness enthusiasts to take their training to the next level.</p>
    </div>

    <div class="copywrite">
        &copy; <?php echo date("Y"); ?> Over and Out. All rights reserved. | Developed by Alex Fife
    </div>
</body>
</html>
