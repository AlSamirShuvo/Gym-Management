
<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'manager') {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['id'];
$manager_info = $conn->query("SELECT * FROM staff WHERE StaffID = '$id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manager Homepage</title>
</head>
<body>
    <h1>Welcome, <?php echo $manager_info['StaffName']; ?></h1>
    <p>Your Information:</p>
    <ul>
        <li>ID: <?php echo $manager_info['StaffID']; ?></li>
        <li>Name: <?php echo $manager_info['StaffName']; ?></li>
        <li>Position: <?php echo $manager_info['Position']; ?></li>
    </ul>

    <h2>Database Management</h2>
    <a href="../crud/add.php">Add</a>
    <a href="../crud/update.php">Update</a>
    <a href="../crud/delete.php">Delete</a>
</body>
</html>
