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

// Fetch manager data from the database
$sql = "SELECT * FROM trainer";
$result = $conn->query($sql);


if (isset($_POST['TrainerID'])) {
    $TrainerIDs = $_POST['TrainerID'];
    $firstNames = $_POST['FirstName'];
    $lastNames = $_POST['LastName'];
    $phones = $_POST['Phone'];
    $emails = $_POST['Email'];
    $streets = $_POST['Street'];
    $cities = $_POST['City'];
    $states = $_POST['State'];
    $countries = $_POST['Country'];
    $passwords = $_POST['Password'];

    for ($i = 0; $i < count($TrainerIDs); $i++) {
        // Update query
        $sql = "UPDATE trainer SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, Password = ? WHERE TrainerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $firstNames[$i], $lastNames[$i], $phones[$i], $emails[$i], $streets[$i], $cities[$i], $states[$i], $countries[$i], $passwords[$i], $TrainerIDs[$i]);
        $stmt->execute();
    }
}

// Delete manager data
if (isset($_POST['delete'])) {
    $deleteIDs = $_POST['delete'];
    foreach ($deleteIDs as $deleteID) {
        // Delete query
        $sql = "DELETE FROM trainer WHERE TrainerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $deleteID);
        $stmt->execute();
    }
}
// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="manager.css">
   
  
   
</head>
<body>
<nav class="navbar">
    <a href="manager.php"><div class="logo">Trainer Dashboard</div></a>
        
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </label>
        <ul class="nav-links">
        <li><a href="managerEditMember.php">member-details</a></li>
        <li><a href="#contact">staff-details</a></li>
        </ul>
        <a href="logout.php">    <button class="cta-btn">Log Out</button></a>
    
    </nav>
    <section class="banner">
        <div class="banner-content">
            <h1>Update your Data</h1>

            <!-- Task 2: Table displaying manager data -->
            <form action="trainer.php" method="POST">
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
    </div>
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
                                echo "<td><button type='submit' name='delete[]' value='" . $trainer['TrainerID'] . "' class='delete-btn'>Delete</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
               
                <button class="cta-btn" type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</body>
</html>
