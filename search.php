
<?php
include 'db.php';
$query = $_GET['query'] ?? '';
$result = $conn->query("SELECT * FROM member WHERE FirsName LIKE '%$query%' OR LastName LIKE '%$query%'");

echo "<h1>Search Results</h1>";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['MemberID'] . ", Name: " . $row['FirsName'] . " " . $row['LastName'] . "<br>";
    }
} else {
    echo "No results found.";
}
?>
