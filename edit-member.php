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

// Fetch the current member data from the database
$sql = "SELECT MemberID, FirstName, LastName, Phone, Email, Street, City, State, Country, PlanID, Password 
        FROM member 
        WHERE MemberID = '$memberID'";

$result = $conn->query($sql);

// Fetch the member data if the query returns a result
if ($result && $result->num_rows > 0) {
    $memberData = $result->fetch_assoc();
} else {
    // If no data is found for the given MemberID
    header("Location: member.php");
    exit;
}

// Initialize variables for form inputs (so they are not empty when submitted)
$firstName = $memberData['FirstName'];
$lastName = $memberData['LastName'];
$phone = $memberData['Phone'];
$email = $memberData['Email'];
$street = $memberData['Street'];
$city = $memberData['City'];
$state = $memberData['State'];
$country = $memberData['Country'];
$planID = $memberData['PlanID'];
$password = $memberData['Password'];

$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated form data
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $street = $_POST['Street'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $country = $_POST['Country'];
    $planID = $_POST['PlanID'];
    $password = $_POST['Password'];

    // Validate the data if needed (you can add more validation here)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $message = "Please fill out all required fields.";
    } else {
        // SQL query to update the member's data
        $updateSql = "UPDATE member 
                      SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, PlanID = ?, Password = ? 
                      WHERE MemberID = '$memberID'";

        // Prepare and bind the statement to prevent SQL injection
        if ($stmt = $conn->prepare($updateSql)) {
            $stmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $planID, $password);
            
            // Execute the query and check if the update was successful
            if ($stmt->execute()) {
                $message = "Information updated successfully!";
            } else {
                $message = "Error updating information. Please try again.";
            }
            
            $stmt->close();
        } else {
            $message = "Error preparing statement.";
        }
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
    <title>Edit Member Information</title>
    <link rel="stylesheet" href="manager.css"> <!-- Use the same or different CSS for member -->
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
        <li><a href="class-info.php">Class Info</a></li>
        <li><a href="trainer-teaches-class.php">Trainer Teaches Class</a></li>
        <li><a href="workout-schedule.php">Workout Schedule</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>
<section class="banner">
    <div class="banner-content">
        <h1>Edit Your Information</h1>

        <!-- Display any message -->
        <?php if ($message): ?>
            <div class="signup-message">
                <h3><?= $message ?></h3> 
            </div>
        <?php endif; ?>

        <!-- Form for updating member data -->
        <form action="edit-member.php" method="POST">
            <label for="FirstName">First Name:</label>
            <input type="text" name="FirstName" value="<?= htmlspecialchars($firstName) ?>" required>

            <label for="LastName">Last Name:</label>
            <input type="text" name="LastName" value="<?= htmlspecialchars($lastName) ?>" required>

            <label for="Phone">Phone:</label>
            <input type="text" name="Phone" value="<?= htmlspecialchars($phone) ?>">

            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="Street">Street:</label>
            <input type="text" name="Street" value="<?= htmlspecialchars($street) ?>">

            <label for="City">City:</label>
            <input type="text" name="City" value="<?= htmlspecialchars($city) ?>">

            <label for="State">State:</label>
            <input type="text" name="State" value="<?= htmlspecialchars($state) ?>">

            <label for="Country">Country:</label>
            <input type="text" name="Country" value="<?= htmlspecialchars($country) ?>">

            <label for="PlanID">Plan ID:</label>
            <input type="text" name="PlanID" value="<?= htmlspecialchars($planID) ?>">

            <label for="Password">Password:</label>
            <input type="password" name="Password" value="<?= htmlspecialchars($password) ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</section>
</body>
</html>














<!-- member -->

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

// SQL query to fetch the member's data
$sql = "SELECT MemberID, FirstName, LastName, Phone, Email, Street, City, State, Country, PlanID, Password 
        FROM member 
        WHERE MemberID = '$memberID'";

// Execute the query
$result = $conn->query($sql);

// Fetch the member data if the query returns a result
if ($result && $result->num_rows > 0) {
    $memberData = $result->fetch_assoc();
} else {
    // If no data is found for the given MemberID
    header("Location: login2.php");
    exit;
}


// Check if the form is submitted
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Get the updated form data
//     $firstName = $_POST['FirstName'];
//     $lastName = $_POST['LastName'];
//     $phone = $_POST['Phone'];
//     $email = $_POST['Email'];
//     $street = $_POST['Street'];
//     $city = $_POST['City'];
//     $state = $_POST['State'];
//     $country = $_POST['Country'];
//     $planID = $_POST['PlanID'];
//     $password = $_POST['Password'];

//     // Validate the data if needed (you can add more validation here)
//     if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
//         $message = "Please fill out all required fields.";
//     } else {
//         // SQL query to update the member's data
//         $updateSql = "UPDATE member 
//                       SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, PlanID = ?, Password = ? 
//                       WHERE MemberID = '$memberID'";

//         // Prepare and bind the statement to prevent SQL injection
//         if ($stmt = $conn->prepare($updateSql)) {
//             $stmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country, $planID, $password);
            
//             // Execute the query and check if the update was successful
//             if ($stmt->execute()) {
//                 $message = "Information updated successfully!";
//             } else {
//                 $message = "Error updating information. Please try again.";
//             }
            
//             $stmt->close();
//         } else {
//             $message = "Error preparing statement.";
//         }
//     }
// }
// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="manager.css"> <!-- You can use the same CSS as trainer.php or modify it for member -->
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
        <li><a href="class-info.php">Class Info</a></li>
        <li><a href="trainer-teaches-class.php">Trainer Teaches Class</a></li>
        <li><a href="workout-schedule.php">Workout Schedule</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>
<section class="banner">
    <div class="banner-content">
        <h1>Welcome, <?= htmlspecialchars($memberData['FirstName']) ?>!</h1>
        
        <h2>Your Member Information</h2>
        <table>
            <thead>
                <tr>
                    <th>MemberID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
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
                <tr>
                    <td><?= htmlspecialchars($memberData['MemberID']) ?></td>
                    <td><?= htmlspecialchars($memberData['FirstName']) ?></td>
                    <td><?= htmlspecialchars($memberData['LastName']) ?></td>
                    <td><?= htmlspecialchars($memberData['Phone']) ?></td>
                    <td><?= htmlspecialchars($memberData['Email']) ?></td>
                    <td><?= htmlspecialchars($memberData['Street']) ?></td>
                    <td><?= htmlspecialchars($memberData['City']) ?></td>
                    <td><?= htmlspecialchars($memberData['State']) ?></td>
                    <td><?= htmlspecialchars($memberData['Country']) ?></td>
                    <td><?= htmlspecialchars($memberData['PlanID']) ?></td>
                    <td><?= htmlspecialchars($memberData['Password']) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Optional: Add a button to allow the member to edit their data -->
        <a href="edit-member.php"><button class="cta-btn">Edit Your Info</button></a>
    </div>
</section>
</body>
</html>











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

// Fetch the logged-in member's data from the database
$sql = "SELECT * FROM member WHERE MemberID = '$memberID'";
$result = $conn->query($sql);

// Check if the member data exists
if ($result && $result->num_rows > 0) {
    $member = $result->fetch_assoc();  // Fetch the logged-in member's data
} else {
    // If no member data found, handle the error (e.g., show a message)
    echo "No member data found.";
    exit;
}

// Handle form submission for updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $street = $_POST['Street'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $country = $_POST['Country'];
    // $planID = $_POST['PlanID'];
 
    $password = $_POST['Password'];

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
                    -- PlanID = ?, 
                    Password = ? 
                  WHERE MemberID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($updateSql);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        // Output the error if the statement preparation failed
        die('Error preparing the query: ' . $conn->error);
    }

    // Bind parameters (using the correct data types: s for string)
    $stmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $street, $city, $state, $country,  $password, $memberID);

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
    <link rel="stylesheet" href="manager.css"> <!-- Use the same or different CSS for member -->
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
        <li><a href="class-info.php">Class Info</a></li>
        <li><a href="trainer-teaches-class.php">Trainer Teaches Class</a></li>
        <li><a href="workout-schedule.php">Workout Schedule</a></li>
    </ul>
    <a href="logout.php"><button class="cta-btn">Log Out</button></a>
</nav>
<section class="banner">
    <div class="banner-content">
        <h1>Welcome, <?php echo htmlspecialchars($member['FirstName']); ?>!</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

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
                    </tbody>
                </table>
                <button class="cta-btn" type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</body>
</html>
