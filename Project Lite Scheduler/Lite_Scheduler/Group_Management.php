<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sengine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting Group Management Data
if(isset($_POST['submit'])){
    $project_id = $_POST['project_id'];
    $category = $_POST['category'];
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];
    $task_id = $_POST['task_id'];

    $sql = "INSERT INTO Group_Management (project_id, category, user_id, role_id, task_id)
            VALUES ('$project_id', '$category', '$user_id', '$role_id', '$task_id')";

if ($conn->query($sql) === TRUE) {
    // Redirect to account.php after successful insertion
    header("Location: group.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>
