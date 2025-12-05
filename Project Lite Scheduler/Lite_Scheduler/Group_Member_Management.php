<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sengine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inserting Group Member Management Data
if(isset($_POST['submit'])){
    $group_id = $_POST['group_id'];
    $groupname = $_POST['groupname'];
    $project_id = $_POST['project_id'];

    $sql = "INSERT INTO Group_Member_Management (group_id, groupname, project_id)
            VALUES ('$group_id', '$groupname', '$project_id')";

if ($conn->query($sql) === TRUE) {
    // Redirect to account.php after successful insertion
    header("Location: group_member.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>
