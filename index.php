<?php 
require 'config.php'; 
session_start(); 

if (isset($_SESSION['imported']) && $_SESSION['imported'] === true) {
    unset($_SESSION['imported']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data Import</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #1F5F1E; 
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input[type="file"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #1F5F1E; 
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #155d14; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #1F5F1E; /* Main color */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1>Import Student Data</h1>
    <form action="" enctype="multipart/form-data" method="post">
        <input type="file" name="excel" required>
        <button type="submit" name="import">Import</button>
    </form>

    <table>
        <tr>
            <th>Id</th>
            <th>User Id</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Section</th>
            <th>Contact Number</th>
            <th>Religion</th>
            <th>Birthday</th>
        </tr>

        <?php
        $i = 1;
        $rows = mysqli_query($conn, "SELECT * FROM student");
        foreach ($rows as $row) :
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row["userid"]; ?></td>
                <td><?php echo $row["firstname"]; ?></td>
                <td><?php echo $row["middlename"]; ?></td>
                <td><?php echo $row["lastname"]; ?></td>
                <td><?php echo $row["age"]; ?></td>
                <td><?php echo $row["sex"]; ?></td>
                <td><?php echo $row["section"]; ?></td>
                <td><?php echo $row["contactnumber"]; ?></td>
                <td><?php echo $row["religion"]; ?></td>
                <td><?php echo $row["birthday"]; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    if (isset($_POST["import"])) {
        $filename = $_FILES["excel"]["name"];
        $fileExtension = explode('.', $filename);
        $fileExtension = strtolower(end($fileExtension));

        $newFileName = date("Y.m.d") . " _ " . date("h.i.sa") . "." . $fileExtension;

        $targetDirectory = "uploads/" . $newFileName;
        move_uploaded_file($_FILES["excel"]["tmp_name"], $targetDirectory);

        error_reporting(0);
        ini_set('display_errors', 0);

        require "excelreader/excel_reader2.php";
        require "excelreader/SpreadsheetReader.php";
        
        $reader = new SpreadsheetReader($targetDirectory);
        $isFirstRow = true; 
        foreach ($reader as $key => $row) {
            if ($isFirstRow) {
                $isFirstRow = false; 
                continue; 
            }

            $username = $row[0];
            $firstname = $row[1];
            $middlename = $row[2];
            $lastname = $row[3];
            $age = $row[4];
            $sex = $row[5];
            $section = $row[6];
            $contactnumber = $row[7];
            $religion = $row[8];
            $birthday = $row[9];
            mysqli_query($conn, "INSERT INTO student VALUES('', '$username', '$firstname', '$middlename', '$lastname', '$age', '$sex', '$section', '$contactnumber', '$religion', '$birthday')");
        }

        $_SESSION['imported'] = true; 
        echo "<script>alert('Successfully imported'); document.location.href='';</script>";
        exit; 
    }
    ?>
</body>
</html>
