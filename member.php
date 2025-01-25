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

// Handle search query
$searchQuery = '';
if (isset($_POST['searchMemberID']) && !empty($_POST['searchMemberID'])) {
    // If search term is provided, filter the results by MemberID
    $searchMemberID = $_POST['searchMemberID'];
    $searchQuery = "AND MemberID LIKE '%$searchMemberID%'";
}

// Fetch the logged-in member's data from the database
$sql = "SELECT * FROM member WHERE MemberID = '$memberID' $searchQuery";
$result = $conn->query($sql);

// Check if the member data exists
if ($result && $result->num_rows > 0) {
    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
} else {
    $members = [];
}

// Handle form submission for updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateMember'])) {
    // Get the updated values from the form
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $street = $_POST['Street'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $country = $_POST['Country'];
    $planID = $_POST['PlanID'];  // PlanID could be empty
    $password = $_POST['Password'];

    // If PlanID is empty, set it to NULL
    if (empty($planID)) {
        $planID = NULL;
    }

    // Prepare the SQL query to update the member's data
    $updateSql = "UPDATE member SET 
                    FirstName = ?, 
                    LastName = ?, 
                    Phone = ?, 
                    Email = ?, 
                    Street = ?, 
                    City = ?, 
                    State = ?, 
                    Country = ?, 
                    Password = ? 
                  WHERE MemberID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($updateSql);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        // Output the error if the statement preparation failed
        die('Error preparing the query: ' . $conn->error);
    }

    // Bind parameters (using the correct data types: s for string, i for integer)
    $stmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $password, $memberID);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Success: Redirect to the same page or show a success message
        $_SESSION['message'] = 'Your data has been updated successfully.';
        header("Location: member.php");  // Redirect to avoid re-posting the form
        exit;
    } else {
        // Error: Show the error message for debugging
        $_SESSION['message'] = 'There was an error updating your data: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="manager.css">
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
        <li><a href="progress-note.php">progress-note</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Welcome, to  Member  data table</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <form action="member.php" method="POST" style="float: right; margin-bottom: 20px;">
            <input type="text" name="searchMemberID" placeholder="Search MemberID" value="<?php echo isset($searchMemberID) ? htmlspecialchars($searchMemberID) : ''; ?>">
            <button type="submit" class="cta-btn">Search</button>
        </form>

        <!-- Form for updating member data -->
        <form action="member.php" method="POST">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>MemberID</th>
                            <th>FirstName</th>
                            <th>LastName</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Street</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>PlanID</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($members) > 0): ?>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><input type='text' name='MemberID' value='<?php echo htmlspecialchars($member['MemberID']); ?>' readonly></td>
                                    <td><input type='text' name='FirstName' value='<?php echo htmlspecialchars($member['FirstName']); ?>'></td>
                                    <td><input type='text' name='LastName' value='<?php echo htmlspecialchars($member['LastName']); ?>'></td>
                                    <td><input type='text' name='Phone' value='<?php echo htmlspecialchars($member['Phone']); ?>'></td>
                                    <td><input type='text' name='Email' value='<?php echo htmlspecialchars($member['Email']); ?>'></td>
                                    <td><input type='text' name='Street' value='<?php echo htmlspecialchars($member['Street']); ?>'></td>
                                    <td><input type='text' name='City' value='<?php echo htmlspecialchars($member['City']); ?>'></td>
                                    <td><input type='text' name='State' value='<?php echo htmlspecialchars($member['State']); ?>'></td>
                                    <td><input type='text' name='Country' value='<?php echo htmlspecialchars($member['Country']); ?>'></td>
                                    <td><input type='text' name='PlanID' value='<?php echo htmlspecialchars($member['PlanID']); ?>'></td>
                                    <td><input type='text' name='Password' value='<?php echo htmlspecialchars($member['Password']); ?>'></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button class="cta-btn" type="submit" name="updateMember">Save Changes</button>
            </form>
        </div>
    </section>
</body>
</html>
