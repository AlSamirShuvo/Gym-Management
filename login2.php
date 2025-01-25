<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $ID = $_POST['ID'];  // ID field can be TrainerID, MemberID, or ManagerID
    $Password = $_POST['Password'];
    $role = $_POST['role'];  // Role selection (trainer, member, manager)

    // SQL query variables
    $sql = '';
    $redirectPage = '';
    
    // Define the query based on the selected role
    if ($role == 'trainer') {
        $sql = "SELECT * FROM trainer WHERE TrainerID = '$ID' AND Password = '$Password'";
        $redirectPage = 'trainer.php';
    } elseif ($role == 'member') {
        $sql = "SELECT * FROM member WHERE MemberID = '$ID' AND Password = '$Password'";
        $redirectPage = 'member.php';
    } elseif ($role == 'manager') {
        $sql = "SELECT * FROM manager WHERE ManagerID = '$ID' AND Password = '$Password'";
        $redirectPage = 'manager.php';
    }

    // Execute the SQL query
    $result = $conn->query($sql);

    // Check if the credentials are valid
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Fetch user data
        // Credentials are correct, redirect to the appropriate page
        $_SESSION['user'] = $ID;  // Store the user ID in session
        $_SESSION['role'] = $role;  // Store the role in session
        $_SESSION['userData'] = $user;  // Store the full user data for use later
        header("Location: $redirectPage");
        exit;
    } else {
        // Credentials are incorrect, show an error message
        $message = "Invalid credentials. Please try again.";
    }
}

// Close the database connection
$conn->close();
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
    <div class="headerline"> <h1 style="font-size: 60px;">Welcome  to  Focus  Gym</h1></div>
    <div class="signup-container">
    <div class="regi-tab">
        <a href="signup.php" id="signup-link">Sign Up</a>
        <a href="login2.php" id="login-link">Login</a>
    </div>

    
        <form action="login2.php" method="POST">
            <div class="form-row">
                <input type="text" name="ID" placeholder="Enter  ID Please" required>
            
            </div>
           
            <input type="text" name="Password" placeholder="Password" required>
          
           
            
            <select name="role" required>
                <option value="trainer">Trainer</option>
                <option value="member">Member</option>
                <option value="manager">Manager</option>
            </select>
            
            <button type="submit">Login</button>
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
            window.location.href = 'signup.php'; // Redirect to signup.php after click
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

