<?php
// Database connection

session_start();
include 'db.php';

// Getting form data from HTML
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Getting form data from HTML
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $Phone = $_POST['Phone'];
    $Email = $_POST['Email'];
    $Street = $_POST['Street'];
    $City = $_POST['City'];
    $State = $_POST['State'];
    $Country = $_POST['Country'];
    $Password = $_POST['Password']; // Encrypt Password
    // $Password = Password_hash($_POST['Password'], Password_DEFAULT); // Encrypt Password
    $role = $_POST['role'];  // Role selection (trainer, member, manager)

    // Default PlanID for member (you might adjust this logic depending on your application)
    // $planID = 1; // You can change this or use a different way to assign PlanID based on your logic

    // Determine which table to insert the data into based on the selected role
    if ($role == 'trainer') {
        $table = 'trainer';
    } elseif ($role == 'manager') {
        $table = 'manager';
    } else {
        $table = 'member'; // Default to inserting into the 'member' table
    }

    // SQL query to insert the data into the corresponding table
    $sql = "INSERT INTO $table (FirstName, LastName, Phone, Email, Street, City, State, Country,  Password) 
        VALUES ('$FirstName', '$LastName', '$Phone', '$Email', '$Street', '$City', '$State', '$Country',  '$Password')";

    // Executing the SQL query
    if ($conn->query($sql) === TRUE) {
        $message = "Registration successful!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="signup.css">


</head>

<body>
    <div class="headerline">
        <h1 style="font-size: 60px;">Welcome to Focus Gym</h1>
    </div>
    <div class="signup-container">
        <div class="regi-tab">
            <a href="signup.php" id="signup-link">Sign Up</a>
            <a href="login2.php" id="login-link">Login</a>
        </div>


        <form action="signup.php" method="POST">
            <div class="form-row">
                <input type="text" name="FirstName" placeholder="First Name" required>
                <input type="text" name="LastName" placeholder="Last Name" required>
            </div>
            <div class="form-row">
                <input type="text" name="Phone" placeholder="Phone" required>
                <input type="email" name="Email" placeholder="Email" required>
            </div>
            <input type="text" name="Street" placeholder="Street" required>
            <div class="form-row">
                <input type="text" name="City" placeholder="City" required>
                <input type="text" name="State" placeholder="State" required>
            </div>
            <input type="text" name="Country" placeholder="Country" required>
            <input type="password" name="Password" placeholder="Password" required>

            <select name="role" required>
                <option value="trainer">Trainer</option>
                <option value="member">Member</option>
                <option value="manager">Manager</option>
            </select>

            <button type="submit">Sign Up</button>
            <?php if ($message): ?>
                <div class="signup-message">
                    <h3><?= $message ?></h3>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script>
        // JavaScript to handle active class toggle
        const signupLink = document.getElementById('signup-link');
        const loginLink = document.getElementById('login-link');

        // Function to reset active states
        function resetActiveLinks() {
            signupLink.classList.remove('active');
            loginLink.classList.remove('active');
        }

        // Set "Sign Up" link as active
        signupLink.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default action (navigation)
            resetActiveLinks();
            signupLink.classList.add('active');
            window.location.href = signup.php; // Redirect to signup.php after click
        });

        // Set "Login" link as active
        loginLink.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default action (navigation)
            resetActiveLinks();
            loginLink.classList.add('active');
            window.location.href = 'login2.php'; // Redirect to login2.php after click
        });

        // Initially set the active state based on URL or default
        if (window.location.href.includes('signup.php')) {
            signupLink.classList.add('active');
        } else if (window.location.href.includes('login2.php')) {
            loginLink.classList.add('active');
        }
    </script>
</body>

</html>