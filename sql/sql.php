<?php

session_start();
$_SESSION['Logged in'] = false;

$servername = "localhost";
$username = "root";
$password = "";
$db = "myData";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  echo `Connection failed: $conn->connect_error`;
} else {
    echo "Connected successfully";
} echo "<br>";

$sql = 'SELECT id, username, password, name, email FROM users';
$result = $conn->query($sql);

$inputs = [];
$inputs['username'] = $_POST['Username'];
$inputs['password'] = $_POST['Password'];

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        // echo "id: " . $row["id"] . " | username: " . $row["username"] . " | password: " . $row["password"] . "<br>";
        if ($inputs['username'] === $row["username"] && $inputs['password'] === $row["password"]) {
            $_SESSION['Logged in'] = true;
            break;
        } else {
            $_SESSION['Logged in'] = false;
        }
    }

    if ($_SESSION['Logged in']) {
        echo "Logged in! <br>";
    } else {
        echo "Failed to loggin! <br>";
    }

    header('Location: http://localhost/functions/upload/');
} else {
    echo "0 results!";
}