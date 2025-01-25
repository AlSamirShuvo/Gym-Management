<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in and if the role is 'trainer'
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'trainer') {
    // Redirect to login page if not logged in or if role is not trainer
    header('Location: login2.php');
    exit;
}

// Get the logged-in trainer's ID from the session
$trainerID = $_SESSION['user'];

// Query to find the class assigned to this trainer
$sql = "
    SELECT c.ClassID, c.ClassName, c.Duration 
    FROM class c
    JOIN trainer_teaches_class ttc ON c.ClassID = ttc.ClassID
    WHERE ttc.TrainerID = '$trainerID'
";

// Execute the query
$result = $conn->query($sql);

// Close the database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Info</title>
    <link rel="stylesheet" href="manager.css">
</head>
<body>
<nav class="navbar">
    <a href="trainer.php"><div class="logo">Trainer Dashboard</div></a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </label>
    <ul class="nav-links">
    <li><a href="class-info.php">class-info</a></li>
        <li><a href="trainer-teaches-class.php">trainer-teaches-class</a></li>
        <li><a href="workout-schedule.php">workout-schedule</a></li>
      
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>
<section class="banner">
    <div class="banner-content">
        <h1>Your Assigned Class</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <!-- Display the assigned class data -->
            <table>
                <thead>
                    <tr>
                        <th>ClassID</th>
                        <th>ClassName</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ClassID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ClassName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Duration']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <!-- If no class is assigned to the trainer -->
            <p>Not assigned to any class.</p>
        <?php endif; ?>
    </div>
</section>
</body>
</html>
