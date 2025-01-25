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

// Initialize the SQL query for fetching manager data
$sql = "SELECT * FROM manager";

// Check if a search has been made
if (isset($_POST['searchManagerID']) && !empty($_POST['searchManagerID'])) {
    $searchManagerID = $_POST['searchManagerID'];
    // Modify the query to include a WHERE clause for filtering by ManagerID
    $sql .= " WHERE ManagerID LIKE '%$searchManagerID%'";
}

// Fetch manager data from the database based on the (optional) search filter
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
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
        <h1>Edit All Manager Data</h1>

        <!-- Search Form for ManagerID -->
        <form action="editmanager.php" method="POST">
            <label for="searchManagerID">Search Manager by ID:</label>
            <input type="text" id="searchManagerID" name="searchManagerID" placeholder="Enter Manager ID" value="<?php echo isset($_POST['searchManagerID']) ? htmlspecialchars($_POST['searchManagerID']) : ''; ?>">
            <button type="submit" class="cta-btn">Search</button>
        </form>

        <!-- Task 2: Table displaying manager data -->
        <form action="update_manager.php" method="POST">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ManagerID</th>
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
                    <tbody
                    <?php
                    // Check if there are results
                    if ($result && $result->num_rows > 0) {
                        while ($manager = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><input type='text' name='ManagerID[]' value='" . htmlspecialchars($manager['ManagerID']) . "' readonly></td>";
                            echo "<td><input type='text' name='FirstName[]' value='" . htmlspecialchars($manager['FirstName']) . "'></td>";
                            echo "<td><input type='text' name='LastName[]' value='" . htmlspecialchars($manager['LastName']) . "'></td>";
                            echo "<td><input type='text' name='Phone[]' value='" . htmlspecialchars($manager['Phone']) . "'></td>";
                            echo "<td><input type='text' name='Email[]' value='" . htmlspecialchars($manager['Email']) . "'></td>";
                            echo "<td><input type='text' name='Street[]' value='" . htmlspecialchars($manager['Street']) . "'></td>";
                            echo "<td><input type='text' name='City[]' value='" . htmlspecialchars($manager['City']) . "'></td>";
                            echo "<td><input type='text' name='State[]' value='" . htmlspecialchars($manager['State']) . "'></td>";
                            echo "<td><input type='text' name='Country[]' value='" . htmlspecialchars($manager['Country']) . "'></td>";
                            echo "<td><input type='text' name='Password[]' value='" . htmlspecialchars($manager['Password']) . "'></td>";
                            echo "<td><button type='submit' name='delete[]' value='" . $manager['ManagerID'] . "' class='delete-btn'>Delete</button></td>";
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

<?php
// Close the database connection
$conn->close();
?>
