
<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'trainer') {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['id'];
$trainer_info = $conn->query("SELECT * FROM trainer WHERE TrainerID = '$id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trainer Homepage</title>
</head>
<body>
    <h1>Welcome, <?php echo $trainer_info['FirstName']; ?></h1>
    <p>Your Information:</p>
    <ul>
        <li>ID: <?php echo $trainer_info['TrainerID']; ?></li>
        <li>Name: <?php echo $trainer_info['FirstName'] . " " . $trainer_info['LastName']; ?></li>
    </ul>
</body>
</html>
