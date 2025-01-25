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
if (isset($_POST['searchTrainerID']) && !empty($_POST['searchTrainerID'])) {
    // If search term is provided, filter the results by TrainerID
    $searchTrainerID = $_POST['searchTrainerID'];
    $searchQuery = "WHERE TrainerID LIKE '%$searchTrainerID%'";
}

// Fetch trainer data from the database based on search query
$sql = "SELECT * FROM trainer $searchQuery";
$result = $conn->query($sql);

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['TrainerID'])) {
    // Collect the form data for updates
    $trainerIDs = $_POST['TrainerID'];
    $firstNames = $_POST['FirstName'];
    $lastNames = $_POST['LastName'];
    $phones = $_POST['Phone'];
    $emails = $_POST['Email'];
    $streets = $_POST['Street'];
    $cities = $_POST['City'];
    $states = $_POST['State'];
    $countries = $_POST['Country'];
    $passwords = $_POST['Password'];

    // Update query for each trainer
    for ($i = 0; $i < count($trainerIDs); $i++) {
        $sql = "UPDATE trainer SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, Password = ? WHERE TrainerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $firstNames[$i], $lastNames[$i], $phones[$i], $emails[$i], $streets[$i], $cities[$i], $states[$i], $countries[$i], $passwords[$i], $trainerIDs[$i]);
        $stmt->execute();
    }
}

// Handle deletion request
if (isset($_POST['deleteTrainerID'])) {
    $deleteID = $_POST['deleteTrainerID'];

    // Delete query
    $sql = "DELETE FROM trainer WHERE TrainerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $deleteID);
    $stmt->execute();
}

// Handle insertion of new trainer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newTrainer'])) {
    // Collect the data for the new trainer
    $firstName = $_POST['NewFirstName'];
    $lastName = $_POST['NewLastName'];
    $phone = $_POST['NewPhone'];
    $email = $_POST['NewEmail'];
    $street = $_POST['NewStreet'];
    $city = $_POST['NewCity'];
    $state = $_POST['NewState'];
    $country = $_POST['NewCountry'];
    $password = $_POST['NewPassword'];

    // Insert the new trainer data into the database
    $sql = "INSERT INTO trainer (FirstName, LastName, Phone, Email, Street, City, State, Country, Password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $password);
    $stmt->execute();
}

// Refresh the data after update or delete or insert
$sql = "SELECT * FROM trainer $searchQuery";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Edit Staff</title>
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
        <li><a href="#contact">Staff Details</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Edit All Trainer Data</h1>

        <!-- Search Bar -->
        <form action="manager-edit-staff.php" method="POST" style="float: right; margin-bottom: 20px;">
            <input type="text" name="searchTrainerID" placeholder="Search TrainerID" value="<?php echo isset($searchTrainerID) ? htmlspecialchars($searchTrainerID) : ''; ?>">
            <button type="submit" class="cta-btn">Search</button>
        </form>

        <form action="manager-edit-staff.php" method="POST">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>TrainerID</th>
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
                            while ($trainer = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='text' name='TrainerID[]' value='" . htmlspecialchars($trainer['TrainerID']) . "' readonly></td>";
                                echo "<td><input type='text' name='FirstName[]' value='" . htmlspecialchars($trainer['FirstName']) . "'></td>";
                                echo "<td><input type='text' name='LastName[]' value='" . htmlspecialchars($trainer['LastName']) . "'></td>";
                                echo "<td><input type='text' name='Phone[]' value='" . htmlspecialchars($trainer['Phone']) . "'></td>";
                                echo "<td><input type='text' name='Email[]' value='" . htmlspecialchars($trainer['Email']) . "'></td>";
                                echo "<td><input type='text' name='Street[]' value='" . htmlspecialchars($trainer['Street']) . "'></td>";
                                echo "<td><input type='text' name='City[]' value='" . htmlspecialchars($trainer['City']) . "'></td>";
                                echo "<td><input type='text' name='State[]' value='" . htmlspecialchars($trainer['State']) . "'></td>";
                                echo "<td><input type='text' name='Country[]' value='" . htmlspecialchars($trainer['Country']) . "'></td>";
                                echo "<td><input type='text' name='Password[]' value='" . htmlspecialchars($trainer['Password']) . "'></td>";
                                echo "<td><button type='submit' name='deleteTrainerID' value='" . $trainer['TrainerID'] . "' class='delete-btn'>Delete</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No data found</td></tr>";
                        }
                        ?>
                        <!-- Empty row for adding a new trainer -->
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
                            <td><button type="submit" name="newTrainer" class="cta-btn">Add Trainer</button></td>
                        </tr>
         
                    </tbody>
                </table>
                <button class="cta-btn" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</section>

</body>
</html>
