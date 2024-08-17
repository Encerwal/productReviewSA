<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #ADD8E6; /* Light blue */
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center; /* Center elements horizontally */
            align-items: center;     /* Center elements vertically */
        }

        nav {
            display: flex;
            justify-content: center; /* Center navigation items */
            align-items: center;
            width: 100%;
            max-width: 1200px;
            flex-direction: column;
        }

        .nav-links {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the links horizontally */
            align-items: center;
        }

        .nav-links li {
            margin: 0 1rem;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #5F9EA0; /* Darker blue */
        }

        /* Hide the checkbox */
        #nav-toggle {
            display: none;
        }

        /* Hamburger menu */
        .nav-toggle-label {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
            text-align: center;
        }

        /* Dropdown styles for mobile */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            .nav-links li {
                margin: 1rem 0;
            }

            .nav-toggle-label {
                display: block;
            }

            /* Show dropdown when checked */
            #nav-toggle:checked + .nav-toggle-label + .nav-links {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <input type="checkbox" id="nav-toggle">
            <label for="nav-toggle" class="nav-toggle-label">&#9776; Menu</label>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
