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

// Fetch the PlanID for the logged-in member from the member table
$sql = "SELECT PlanID FROM member WHERE MemberID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();

// Check if the member has a PlanID assigned
if ($result && $result->num_rows > 0) {
    $memberData = $result->fetch_assoc();
    $planID = $memberData['PlanID'];  // Get the PlanID assigned to the member

    // Now fetch the corresponding membership plan details from the membership_plan table
    if ($planID) {
        $planSql = "SELECT * FROM membership_plan WHERE PlanID = ?";
        $planStmt = $conn->prepare($planSql);
        $planStmt->bind_param("i", $planID);
        $planStmt->execute();
        $planResult = $planStmt->get_result();

        if ($planResult && $planResult->num_rows > 0) {
            $planData = $planResult->fetch_assoc();  // Fetch the plan data
        } else {
            $planData = null;  // No plan data found
        }
    } else {
        $planData = null;  // No plan assigned
    }
} else {
    $planData = null;  // Member not found or no PlanID
}

// Close the database connection
$stmt->close();
$planStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plan</title>
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
        <h1>Membership Plan Information</h1>

        <?php if ($planData): ?>
            <table>
                <thead>
                    <tr>
                        <th>PlanID</th>
                        <th>Plan Name</th>
                        <th>Cost</th>
                        <th>FacilityID</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($planData['PlanID']); ?></td>
                        <td><?php echo htmlspecialchars($planData['Plan_Name']); ?></td>
                        <td><?php echo htmlspecialchars($planData['Cost']); ?></td>
                        <td><?php echo htmlspecialchars($planData['FacilityID']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">You are not assigned to any membership plan.</div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
