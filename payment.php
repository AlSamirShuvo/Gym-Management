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

// Fetch payment data for the logged-in member from the payment table
$sql = "SELECT * FROM payment WHERE MemberID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any payments for the logged-in member
$payments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;  // Store payment data in an array
    }
} else {
    $payments = null;  // No payments found
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
    <title>Payment History</title>
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
        <h1>Payment History</h1>

        <?php if ($payments): ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Date of Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['PaymentID']); ?></td>
                            <td><?php echo htmlspecialchars($payment['Amount']); ?></td>
                            <td><?php echo htmlspecialchars($payment['Date_of_payment']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">You have not made any payments yet.</div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
