<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: login2.php');
    exit;
}

// Get the ManagerID from session or query parameter
$managerID = $_SESSION['user']; // Assuming the ManagerID is stored in the session

// SQL query to fetch the manager's data
$sql = "SELECT * FROM manager WHERE ManagerID = '$managerID'";
$result = $conn->query($sql);

// Check if the query was successful and if data was found
if ($result && $result->num_rows > 0) {
    // Fetch the manager's data from the result
    $manager = $result->fetch_assoc();
} else {
    // Handle case where no data was found
    $message = "Manager data not found.";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="manager.css">
</head>
<body>
<nav class="navbar">
        <div class="logo">Manger Dashboard</div>
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </label>
        <ul class="nav-links">
            
            <li><a href="managerEditMember.php">member-details</a></li>
            <li><a href="manager-edit-staff.php">staff-details</a></li>
        </ul>
        <a href="logout.php">    <button class="cta-btn">Log Out</button></a>
    </nav>
    <section class="banner">
        <div class="banner-content">
            <h1>Welcome to Focus  Fitness and Gym</h1>
          

            <p>ManagerID: <?php echo htmlspecialchars($manager['ManagerID']); ?></p>
            <p>FirstName: <?php echo htmlspecialchars($manager['FirstName']); ?></p>
            <p>LastName: <?php echo htmlspecialchars($manager['LastName']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($manager['Phone']); ?></p>
            <p>Email: <?php echo htmlspecialchars($manager['Email']); ?></p>
            <p>Street: <?php echo htmlspecialchars($manager['Street']); ?></p>
            <p>City: <?php echo htmlspecialchars($manager['City']); ?></p>
            <p>State: <?php echo htmlspecialchars($manager['State']); ?></p>
            <p>Country: <?php echo htmlspecialchars($manager['Country']); ?></p>
            <p>Password: <?php echo htmlspecialchars($manager['Password']); ?></p>
           
           <a href="editmanager.php"><button class="banner-btn">Edit All manager </button></a>
            
        </div>
        <div class="banner-image">
           
            <img src="https://plus.unsplash.com/premium_photo-1661920538067-c48451160c72?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8Z3ltfGVufDB8fDB8fHww" alt="Blockchain Illustration">
        </div>
    </section>
</body>
</html>