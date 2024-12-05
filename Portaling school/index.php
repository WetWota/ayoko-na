<?php
// Start output buffering
ob_start();
error_reporting( ~E_DEPRECATED);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Portal - Login</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form id="loginForm" action="index.php" method="POST">
            <label for="ID">ID</label>
            <input type="ID" id="ID" name="ID" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            <a href="register.php">Register</a>
        </div>
    </div>

    <?php
    // PHP code to handle login
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ID = $_POST['ID'];
        $password = $_POST['password'];
        
        // Read the accounts.csv file
        $file = fopen('assets/Database/accounts.csv', 'r');
        $login_success = false;

        while (($line = fgetcsv($file)) !== FALSE) {
            // Assuming the CSV format is: username,name,password,account_type,date_created
            if ($line[0] == $ID && $line[2] == $password) {
                // Login successful
                $login_success = true;
                break;
            }
        }
        fclose($file);

        if ($login_success) {
            // Redirect to home page after successful login
            header('Location: Home.php');
            session_start();
            $_SESSION['account_type'] = $line[3];
            $_SESSION['ID'] = $ID;
            $_SESSION['username'] = $line[1];
            exit();
        } else {
            echo "<script>alert('Invalid username or password.');</script>";
        }
    }
    ?>

<?php
// Flush the output buffer
ob_end_flush();
?>
</body>
</html>