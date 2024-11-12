<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="design.css">
    <style>
        /* Error and Success Message Styling */
        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 1em;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="index.php" id="homeButton" class="home-button">Home</a>

    <!-- Reset Password Modal -->
    <div class="modal show">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reset Password</h2>
            </div>
            <div class="modal-body">
                <p>Enter your username and a new password to reset your account password.</p>

                <form method="POST" action="forgot-password.php" onsubmit="return validateResetPasswordForm()">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required><br><br>

                    <label for="newPassword">New Password</label>
                    <input type="password" name="newPassword" id="newPassword" placeholder="Enter a new password" required><br><br>

                    <button type="submit" name="resetPasswordSubmit">Reset Password</button>
                </form>

                <!-- Success/Error Message -->
                <?php if (isset($message)) { echo "<p class='message success'>$message</p>"; } ?>
                <?php if (isset($error)) { echo "<p class='message error'>$error</p>"; } ?>

                <p><a href="register.php">Back to Sign In</a></p>
            </div>
        </div>
    </div>

    <script>
        // Client-side JavaScript validation for reset password form
        function validateResetPasswordForm() {
            const username = document.getElementById("username").value;
            const newPassword = document.getElementById("newPassword").value;
            let isValid = true;

            // Check if the password is at least 8 characters long
            if (newPassword.length < 8) {
                alert("Password must be at least 8 characters long.");
                isValid = false;
            }

            // Validate alphanumeric characters in username and password
            if (!/^[a-zA-Z0-9]+$/.test(username) || !/^[a-zA-Z0-9]+$/.test(newPassword)) {
                alert("Username and password must contain only alphanumeric characters.");
                isValid = false;
            }

            return isValid;
        }
    </script>

    <?php
// Database connection
$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "emp_db";

$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName, 3306);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle reset password form submission
if (isset($_POST['resetPasswordSubmit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);  // Sanitize input
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);  // Sanitize input

    // Validate password length
    if (strlen($newPassword) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if the username exists in the database
        $checkUser = "SELECT * FROM tbl_employee WHERE employee_Username = '$username'";
        $result = mysqli_query($conn, $checkUser);

        if (mysqli_num_rows($result) > 0) {
            // Directly update the password (no hashing)
            $updatePassword = "UPDATE tbl_employee SET employee_Password = '$newPassword' WHERE employee_Username = '$username'";

            if (mysqli_query($conn, $updatePassword)) {
                $message = "Password reset successful! You can now sign in with your new password.";
            } else {
                $error = "Error: Could not update password. Please try again.";
            }
        } else {
            $error = "Error: Username not found.";
        }
    }
}

mysqli_close($conn);
?>

</body>
</html>
