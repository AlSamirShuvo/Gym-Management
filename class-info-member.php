<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in and if the role is 'member'
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'member') {
    // Redirect to login page if not logged in or if role is not member
    header('Location: login2.php');
    exit;
}

// Get the logged-in member's ID from the session
$memberID = $_SESSION['user'];

// Fetch the classes that the member is enrolled in
$sql = "
    SELECT c.ClassID, c.ClassName, c.Duration 
    FROM class c
    INNER JOIN enrolls_in e ON c.ClassID = e.ClassID
    WHERE e.MemberID = ?";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();

// Check if the member is enrolled in any classes
if ($result && $result->num_rows > 0) {
    $classes = $result->fetch_all(MYSQLI_ASSOC);  // Fetch all class data
} else {
    $classes = [];  // No classes assigned
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Info</title>
    <link rel="stylesheet" href="manager.css"> <!-- Adjust the CSS file path as needed -->
</head>
<body>
<nav class="navbar">
    <a href="member.php"><div class="logo">Member Dashboard</div></a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </label>
    <ul class="nav-links">
    <li><a href="class-info-member.php">Class-Info</a></li>
        <li><a href="enrolls-in.php">enrolls-in</a></li>
        <li><a href="membership_plan.php">membership_plan</a></li>
        <li><a href="payment.php">payment</a></li>
        <li><a href="progress-note.php">progess-note</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Class Information</h1>

        <?php if (empty($classes)): ?>
            <div class="message">You are not assigned to any class.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ClassID</th>
                        <th>ClassName</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($class['ClassID']); ?></td>
                            <td><?php echo htmlspecialchars($class['ClassName']); ?></td>
                            <td><?php echo htmlspecialchars($class['Duration']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
</body>
</html>
