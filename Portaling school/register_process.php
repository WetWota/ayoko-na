<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST['id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $accountType = trim($_POST['account_type']);
    $date_created = date('Y-m-d H:i:s');

    // Save to account.csv
    $file = fopen("assets/Database/accounts.csv", "a");
    fputcsv($file, [$id, $username, $password, $accountType, $date_created]);
    fclose($file);

    // Update pre_reg.csv to set bool to 1
    $updatedData = [];
    if (($handle = fopen("assets/Database/pre_reg.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[0] == $id) {
                // Set bool to 1 for the registered ID
                $data[3] = 1; // Assuming the boolean is in the 4th column (index 3)
            }
            $updatedData[] = $data; // Store the updated data
        }
        fclose($handle);
    }

    // Write the updated data back to pre_reg.csv
    if (($handle = fopen("assets/Database/pre_reg.csv", "w")) !== FALSE) {
        foreach ($updatedData as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }

    header("Location: login.php");
    exit();
}

ob_end_flush();
?>