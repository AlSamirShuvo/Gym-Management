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

// Fetch the enrollment records for the logged-in member
$sql = "
    SELECT e.EnrollsID, e.ClassID, e.MemberID, c.ClassName, c.Duration 
    FROM enrolls_in e
    INNER JOIN class c ON e.ClassID = c.ClassID
    WHERE e.MemberID = ?";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();

// Check if the member is enrolled in any classes
if ($result && $result->num_rows > 0) {
    $enrollments = $result->fetch_all(MYSQLI_ASSOC);  // Fetch all enrollment data
} else {
    $enrollments = [];  // No enrollments found
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
    <title>Enrollment Info</title>
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
        <h1>Enrollment Information</h1>

        <?php if (empty($enrollments)): ?>
            <div class="message">You are not enrolled in any classes.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>EnrollsID</th>
                        <th>MemberID</th>  <!-- Display MemberID -->
                        <th>ClassID</th>
                        <th>ClassName</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($enrollment['EnrollsID']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['MemberID']); ?></td>  <!-- Display MemberID -->
                            <td><?php echo htmlspecialchars($enrollment['ClassID']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['ClassName']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['Duration']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
</body>
</html>
