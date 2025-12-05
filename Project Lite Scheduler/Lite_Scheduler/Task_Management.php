<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sengine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting Task Data
if(isset($_POST['submit'])){
    $task_id = $_POST['task_id'];
    $task_end_date = $_POST['task_end_date'];
    $task_start_date = $_POST['task_start_date'];
    $task_description = $_POST['task_description'];
    $role_id = $_POST['role_id'];
    $category = $_POST['category'];

    $sql = "INSERT INTO Task (task_id, task_end_date, task_start_date, task_description, role_id, category)
            VALUES ('$task_id', '$task_end_date', '$task_start_date', '$task_description', '$role_id', '$category')";

if ($conn->query($sql) === TRUE) {
    // Redirect to account.php after successful insertion
    header("Location: task.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>
