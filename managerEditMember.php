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

// Handle search query
$searchQuery = '';
if (isset($_POST['searchMemberID']) && !empty($_POST['searchMemberID'])) {
    // If search term is provided, filter the results by MemberID
    $searchMemberID = $_POST['searchMemberID'];
    $searchQuery = "WHERE MemberID LIKE '%$searchMemberID%'";
}

// Update member data
if (isset($_POST['MemberID'])) {
    $memberIDs = $_POST['MemberID'];
    $firstNames = $_POST['FirstName'];
    $lastNames = $_POST['LastName'];
    $phones = $_POST['Phone'];
    $emails = $_POST['Email'];
    $streets = $_POST['Street'];
    $cities = $_POST['City'];
    $states = $_POST['State'];
    $countries = $_POST['Country'];
    $passwords = $_POST['Password'];

    for ($i = 0; $i < count($memberIDs); $i++) {
        // Update query
        $sql = "UPDATE member SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, Password = ? WHERE MemberID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $firstNames[$i], $lastNames[$i], $phones[$i], $emails[$i], $streets[$i], $cities[$i], $states[$i], $countries[$i], $passwords[$i], $memberIDs[$i]);
        $stmt->execute();
    }
}

// Delete member data
if (isset($_POST['delete'])) {
    $deleteIDs = $_POST['delete'];
    foreach ($deleteIDs as $deleteID) {
        // Delete query
        $sql = "DELETE FROM member WHERE MemberID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $deleteID);
        $stmt->execute();
    }
}

// Handle insertion of new member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newMember'])) {
    // Collect the data for the new member
    $firstName = $_POST['NewFirstName'];
    $lastName = $_POST['NewLastName'];
    $phone = $_POST['NewPhone'];
    $email = $_POST['NewEmail'];
    $street = $_POST['NewStreet'];
    $city = $_POST['NewCity'];
    $state = $_POST['NewState'];
    $country = $_POST['NewCountry'];
    $password = $_POST['NewPassword'];

    // Insert the new member data into the database
    $sql = "INSERT INTO member (FirstName, LastName, Phone, Email, Street, City, State, Country, Password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $password);
    $stmt->execute();
}

// Fetch member data from the database with or without search query
$sql = "SELECT * FROM member $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <link rel="stylesheet" href="manager.css">
</head>
<body>
<nav class="navbar">
    <a href="manager.php"><div class="logo">Manager Dashboard</div></a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </label>
    <ul class="nav-links">
        <li><a href="managerEditMember.php">Member Details</a></li>
        <li><a href="manager-edit-staff.php">Staff Details</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Edit All Member Data</h1>

        <!-- Search Bar -->
        <form action="managerEditMember.php" method="POST" style="float: right; margin-bottom: 20px;">
            <input type="text" name="searchMemberID" placeholder="Search MemberID" value="<?php echo isset($searchMemberID) ? htmlspecialchars($searchMemberID) : ''; ?>">
            <button type="submit" class="cta-btn">Search</button>
        </form>

        <form action="managerEditMember.php" method="POST">
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
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results
                        if ($result && $result->num_rows > 0) {
                            while ($member = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='text' name='MemberID[]' value='" . htmlspecialchars($member['MemberID']) . "' readonly></td>";
                                echo "<td><input type='text' name='FirstName[]' value='" . htmlspecialchars($member['FirstName']) . "'></td>";
                                echo "<td><input type='text' name='LastName[]' value='" . htmlspecialchars($member['LastName']) . "'></td>";
                                echo "<td><input type='text' name='Phone[]' value='" . htmlspecialchars($member['Phone']) . "'></td>";
                                echo "<td><input type='text' name='Email[]' value='" . htmlspecialchars($member['Email']) . "'></td>";
                                echo "<td><input type='text' name='Street[]' value='" . htmlspecialchars($member['Street']) . "'></td>";
                                echo "<td><input type='text' name='City[]' value='" . htmlspecialchars($member['City']) . "'></td>";
                                echo "<td><input type='text' name='State[]' value='" . htmlspecialchars($member['State']) . "'></td>";
                                echo "<td><input type='text' name='Country[]' value='" . htmlspecialchars($member['Country']) . "'></td>";
                                echo "<td><input type='text' name='Password[]' value='" . htmlspecialchars($member['Password']) . "'></td>";
                                echo "<td><button type='submit' name='delete[]' value='" . $member['MemberID'] . "' class='delete-btn'>Delete</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No data found</td></tr>";
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="text" name="NewFirstName" placeholder="First Name"></td>
                            <td><input type="text" name="NewLastName" placeholder="Last Name"></td>
                            <td><input type="text" name="NewPhone" placeholder="Phone"></td>
                            <td><input type="text" name="NewEmail" placeholder="Email"></td>
                            <td><input type="text" name="NewStreet" placeholder="Street"></td>
                            <td><input type="text" name="NewCity" placeholder="City"></td>
                            <td><input type="text" name="NewState" placeholder="State"></td>
                            <td><input type="text" name="NewCountry" placeholder="Country"></td>
                            <td><input type="text" name="NewPassword" placeholder="Password"></td>
                            <td><button type="submit" name="newMember" class="cta-btn">Add Member</button></td>
                        </tr>
                    </tbody>
                </table>
                <button class="cta-btn" type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
