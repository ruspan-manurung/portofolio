<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sengine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting Role Data
if(isset($_POST['submit'])){
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];

    $sql = "INSERT INTO Role (role_id, role_name)
            VALUES ('$role_id', '$role_name')";

if ($conn->query($sql) === TRUE) {
    // Redirect to account.php after successful insertion
    header("Location: role.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>
