<?php
ob_start();
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 1);
session_start();

$error = '';
$id = '';
$accountType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $passcode = trim($_POST['passcode']);

    // Read pre_reg.csv to validate the passcode
    if (($handle = fopen("assets/Database/pre_reg.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[2] == $passcode) {
                if ($data[3] == 1) {
                    $error = "Passcode is invalid.";
                    break;
                } else {
                    $id = $data[0];
                    $accountType = $data[1];
                    break;
                }
            }
        }
        fclose($handle);
    } else {
        $error = "Failed to open pre_reg.csv file.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($id && $accountType): ?>
        <form method="POST" action="register_process.php">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>" readonly>
            <br><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="account_type">Account Type:</label>
            <input type="text" id="account_type" name="account_type" value="<?php echo htmlspecialchars($accountType); ?>" readonly>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <label for="verify_password">Verify Password:</label>
            <input type="password" id="verify_password" name="verify_password" required>
            <br><br>
            <button type="submit" id="register_button" disabled>Register</button>
            <script>
                const password = document.getElementById('password');
                const verifyPassword = document.getElementById('verify_password');
                const registerButton = document.getElementById('register_button');

                password.addEventListener('input', checkPasswords);
                verifyPassword.addEventListener('input', checkPasswords);

                function checkPasswords() {
                    if (password.value === verifyPassword.value) {
                        registerButton.disabled = false;
                    } else {
                        registerButton.disabled = true;
                    }
                }
            </script>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <label for="passcode">Enter Passcode:</label>
            <input type="text" id="passcode" name="passcode" required>
            <br><br>
            <button type="submit">Submit</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
ob_end_flush();
?>