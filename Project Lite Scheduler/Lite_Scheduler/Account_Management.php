<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sengine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting Account Data
if(isset($_POST['submit'])){
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $pass_id = $_POST['pass_id'];
    $task_id = $_POST['task_id'];
    $role_id = $_POST['role_id'];
    $group_id = $_POST['group_id'];

    $sql = "INSERT INTO Account (user_id, username, pass_id, task_id, role_id, group_id)
            VALUES ('$user_id', '$username', '$pass_id', '$task_id', '$role_id', '$group_id')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to account.php after successful insertion
        header("Location: account.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
