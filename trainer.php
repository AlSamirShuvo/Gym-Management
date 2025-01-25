<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in and if the role is 'trainer'
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'trainer') {
    // Redirect to login page if not logged in or if role is not trainer
    header('Location: login2.php');
    exit;
}

// Get the logged-in trainer's ID from the session
$trainerID = $_SESSION['user'];

// Handle search query
$searchQuery = '';
if (isset($_POST['searchTrainerID']) && !empty($_POST['searchTrainerID'])) {
    // If search term is provided, filter the results by TrainerID
    $searchTrainerID = $_POST['searchTrainerID'];
    $searchQuery = "AND TrainerID LIKE '%$searchTrainerID%'";
}

// Fetch the logged-in trainer's data from the database
$sql = "SELECT * FROM trainer WHERE TrainerID = '$trainerID' $searchQuery";
$result = $conn->query($sql);

// Check if the trainer data exists
if ($result && $result->num_rows > 0) {
    $trainers = [];
    while ($row = $result->fetch_assoc()) {
        $trainers[] = $row;
    }
} else {
    $trainers = [];
}

// Handle form submission for updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateTrainer'])) {
    // Get the updated values from the form
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $street = $_POST['Street'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $country = $_POST['Country'];
    $password = $_POST['Password'];

    // Prepare the SQL query to update the trainer's data
    $updateSql = "UPDATE trainer SET 
                    FirstName = ?, 
                    LastName = ?, 
                    Phone = ?, 
                    Email = ?, 
                    Street = ?, 
                    City = ?, 
                    State = ?, 
                    Country = ?, 
                    Password = ? 
                  WHERE TrainerID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $password, $trainerID);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Success: Redirect to the same page or show a success message
        $_SESSION['message'] = 'Your data has been updated successfully.';
        header("Location: trainer.php");  // Redirect to avoid re-posting the form
        exit;
    } else {
        // Error: Show an error message
        $_SESSION['message'] = 'There was an error updating your data.';
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
    <title>Trainer Dashboard</title>
    <link rel="stylesheet" href="manager.css">
</head>
<body>
<nav class="navbar">
    <a href="trainer.php"><div class="logo">Trainer Dashboard</div></a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </label>
    <ul class="nav-links">
        <li><a href="class-info.php">class-info</a></li>
        <li><a href="trainer-teaches-class.php">trainer-teaches-class</a></li>
        <li><a href="workout-schedule.php">workout-schedule</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>

<section class="banner">
    <div class="banner-content">
        <h1>Welcome, trainer  profile</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <!-- <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> -->
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <form action="trainer.php" method="POST" style="float: right; margin-bottom: 20px;">
            <input type="text" name="searchTrainerID" placeholder="Search TrainerID" value="<?php echo isset($searchTrainerID) ? htmlspecialchars($searchTrainerID) : ''; ?>">
            <button type="submit" class="cta-btn">Search</button>
        </form>

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($trainers) > 0): ?>
                            <?php foreach ($trainers as $trainer): ?>
                                <tr>
                                    <td><input type='text' name='TrainerID' value='<?php echo htmlspecialchars($trainer['TrainerID']); ?>' readonly></td>
                                    <td><input type='text' name='FirstName' value='<?php echo htmlspecialchars($trainer['FirstName']); ?>'></td>
                                    <td><input type='text' name='LastName' value='<?php echo htmlspecialchars($trainer['LastName']); ?>'></td>
                                    <td><input type='text' name='Phone' value='<?php echo htmlspecialchars($trainer['Phone']); ?>'></td>
                                    <td><input type='text' name='Email' value='<?php echo htmlspecialchars($trainer['Email']); ?>'></td>
                                    <td><input type='text' name='Street' value='<?php echo htmlspecialchars($trainer['Street']); ?>'></td>
                                    <td><input type='text' name='City' value='<?php echo htmlspecialchars($trainer['City']); ?>'></td>
                                    <td><input type='text' name='State' value='<?php echo htmlspecialchars($trainer['State']); ?>'></td>
                                    <td><input type='text' name='Country' value='<?php echo htmlspecialchars($trainer['Country']); ?>'></td>
                                    <td><input type='text' name='Password' value='<?php echo htmlspecialchars($trainer['Password']); ?>'></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10">No results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button class="cta-btn" type="submit" name="updateTrainer">Save Changes</button>
            </form>
        </div>
    </section>
</body>
</html>
