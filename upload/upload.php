<?php

session_start();

if ($_SESSION['logged in'] = null) $_SESSION['logged in'] = false; 
if ($_SESSION['Logged in'] = null) $_SESSION['Logged in'] = false; 

function logInfoToTextFile(string $filePath = "logs.txt", string $string) {
    $path = fopen($filePath, 'r') or die("Unable to Open File!");
    if (flock($path, LOCK_EX)) {
        $logMsg = "$string \r\n";
        $path = fopen($filePath, 'a') or die("Unable to Open File!");
        fwrite($path, $logMsg);
    }
}

if ($_SESSION['logged in'] || $_SESSION['Logged in']) {
    $user = $_SESSION['user'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    logInfoToTextFile("logs.txt", "$user: $target_file");
    // echo ("<br>$target_file<br>");
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ". <br>";
            $uploadOk = 1;
        } else {
            echo "File is not an image. <br>";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists. <br>";
        $uploadOk = 0;
    }

    // Check file size
    $one_kB = 1024;
    $file_size_limit = 500 * $one_kB;
    if ($_FILES["fileToUpload"]["size"] > $file_size_limit) {
        echo "Sorry, your file is too large. <br>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    if (
        $imageFileType != "jpg"
        && $imageFileType != "png"
        && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. <br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. <br>";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. <br>";
        } else {
            echo "Sorry, there was an error uploading your file. <br>";
        }
    }
} else {
    echo "You are not logged in!";
}