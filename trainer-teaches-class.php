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

// Query to find the trainer's classes from trainer_teaches_class table
$sql = "
    SELECT TrainerTeachesClassID, ClassID, TrainerID
    FROM trainer_teaches_class
    WHERE TrainerID = '$trainerID'
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
    <title>Trainer Teaches Class</title>
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
        <li><a href="class-info.php">Class Info</a></li>
        <li><a href="trainer-teaches-class.php">Trainer Teaches Class</a></li>
        <li><a href="workout-schedule.php">Workout Schedule</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>
<section class="banner">
    <div class="banner-content">
        <h1>Trainer Teaches Classes</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <!-- Display the assigned trainer-teaches-class data -->
            <table>
                <thead>
                    <tr>
                        <th>TrainerTeachesClassID</th>
                        <th>ClassID</th>
                        <th>TrainerID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['TrainerTeachesClassID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ClassID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TrainerID']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <!-- If no data found, show a message -->
            <p>You are not assigned to teach any class yet.</p>
        <?php endif; ?>
    </div>
</section>
</body>
</html>
