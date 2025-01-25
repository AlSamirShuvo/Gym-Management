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

// Fetch progress notes for the logged-in member from the progress_note table
$sql = "SELECT * FROM progress_note WHERE MemberID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any progress notes for the logged-in member
$progressNotes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $progressNotes[] = $row;  // Store progress note data in an array
    }
} else {
    $progressNotes = null;  // No progress notes found
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Notes</title>
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
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Progress Notes</h1>

        <?php if ($progressNotes): ?>
            <table>
                <thead>
                    <tr>
                        <th>Note ID</th>
                        <th>Note</th>
                        <th>Date of Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($progressNotes as $note): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['NoteID']); ?></td>
                            <td><?php echo htmlspecialchars($note['Note']); ?></td>
                            <td><?php echo htmlspecialchars($note['Date_of_note']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">No progress notes available.</div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
