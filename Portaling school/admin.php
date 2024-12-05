<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 1);
session_start();

if ($_SESSION['account_type'] !== 'admin') {
    header("Location: Home.php");
    exit();
}

function generatePasscode($length = 10) {
    return bin2hex(random_bytes($length / 2)); // Generates a random string of the specified length
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST['id']);
    $accountType = $_POST['account_type'];
    $passcode = generatePasscode(10);
    $bool = 0;

    // Validate ID
    if (!preg_match('/^\d{6}$/', $id)) {
        $error = "ID must be a 6-digit integer.";
    } else {
        // Check if ID is unique
        $isUnique = true;
        if (($handle = fopen("assets/Database/pre_reg.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($data[0] == $id) { // Assuming ID is the first column
                    $isUnique = false;
                    break;
                }
            }
            fclose($handle);
        }

        // If ID is unique, save to CSV
        if ($isUnique) {
            $file = fopen("assets/Database/pre_reg.csv", "a");
            fputcsv($file, [$id, $accountType, $passcode,$bool]);
            fclose($file);
            $success = "Account created successfully with ID: $id and Passcode: $passcode";
        } else {
            $error = "ID already exists. Please use a unique ID.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    <h1>Create Account</h1>
    <form method="POST" action="">
        <label for="id">Enter 6-Digit ID:</label>
        <input type="text" id="id" name="id" required pattern="\d{6}" title="Please enter a 6-digit integer.">
        <br><br>
        
        <label for="account_type">Select Account Type:</label>
        <select id="account_type" name="account_type" required>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="admin">Admin</option>
        </select>
        <br><br>
        
        <button type="submit">Generate Passcode</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
</body>
</html>

<?php

?>