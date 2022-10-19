<?php

// $servername = "localhost";
// $username = "root";
// $password = "";
// $db = "myData";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $db);

// // Check connection
// if ($conn->connect_error) {
//   echo ("Connection failed: " . $conn->connect_error);
// } else {
//     echo "Connected successfully";
// } echo "<br>";

// $sql = 'SELECT id, username, password, name, email FROM users';
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     // output data of each row
//     while ($row = $result->fetch_assoc()) {
//         if ($row["username"] === $_POST["Username"] && $row["password"] === $_POST["Password"])
//     }
// } else {
//     echo "0 results!";
// }

session_start();

$_SESSION['logged in'] = false;

function returnUsersAndInputs(string $filePath = "text/userInfo.txt") {
    $inputs = [$_POST['Username'], $_POST['Password']];
    $path = fopen($filePath, 'r') or die("Unable to Open File!");
    if (flock($path, LOCK_EX)) {
        
        return [getUsers($path), $inputs];

    }
}

function getUsers($path) {
    $userInfo = [];
    while (($buffer = fgets($path)) !== false) {

        // echo $buffer . "<br>";
        $row = explode(";", $buffer);
        $row[1] = trim($row[1]);
        $userInfo[$row[0]] = $row[1];
        
    }

    return $userInfo;
}

function logInfoInTextFile(string $filePath = "text/logs.txt", array $nestArray, $success) {
    $path = fopen($filePath, 'r') or die("Unable to Open File!");
    $username = $nestArray[1][0];
    $newURL = 'http://localhost/functions/upload/';
    if (flock($path, LOCK_EX)) {
        if ($success) {
            $logMsg = "Logged in as " . $username . "\r\n";
            $_SESSION['logged in'] = true;
            $_SESSION['user'] = $_POST['Username'];
            header('Location: ' . $newURL);
        } else {
            $logMsg = "Failed to log in as " . $username . "\r\n";
        }
        $path = fopen($filePath, 'a') or die("Unable to Open File!");
        fwrite($path, $logMsg);
        echo $logMsg . "<br>";
    }
}

$nestArray = returnUsersAndInputs();
$userInfo = $nestArray[0];
$inputs = $nestArray[1];
$submitted_username = $inputs[0];
$submitted_password = $inputs[1];

$_SESSION['nestArray'] = $nestArray;

$correctUsername = true;

foreach ($userInfo as $username => $password) {
    if ($submitted_username == $username) {
        if ($submitted_password == $password) {
            logInfoInTextFile("text/logs.txt", $nestArray, true);
        } else {
            logInfoInTextFile("text/logs.txt", $nestArray, false);
        }
        $correctUsername = true;
        break;
    } else {
        $correctUsername = false;
    }
} if (!$correctUsername) {
    logInfoInTextFile("text/logs.txt", $nestArray, NULL);
}