<?php
// Include database connection
include 'db.php';

// Update manager data
if (isset($_POST['ManagerID'])) {
    $managerIDs = $_POST['ManagerID'];
    $firstNames = $_POST['FirstName'];
    $lastNames = $_POST['LastName'];
    $phones = $_POST['Phone'];
    $emails = $_POST['Email'];
    $streets = $_POST['Street'];
    $cities = $_POST['City'];
    $states = $_POST['State'];
    $countries = $_POST['Country'];
    $passwords = $_POST['Password'];

    for ($i = 0; $i < count($managerIDs); $i++) {
        // Update query
        $sql = "UPDATE manager SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Street = ?, City = ?, State = ?, Country = ?, Password = ? WHERE ManagerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $firstNames[$i], $lastNames[$i], $phones[$i], $emails[$i], $streets[$i], $cities[$i], $states[$i], $countries[$i], $passwords[$i], $managerIDs[$i]);
        $stmt->execute();
    }
}

// Delete manager data
if (isset($_POST['delete'])) {
    $deleteIDs = $_POST['delete'];
    foreach ($deleteIDs as $deleteID) {
        // Delete query
        $sql = "DELETE FROM manager WHERE ManagerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $deleteID);
        $stmt->execute();
    }
}

// Redirect back to the edit manager page
header('Location: editmanager.php');
exit;
?>
