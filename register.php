<?php
session_start();

// Database connection
$DBHost = "localhost";    
$DBUser = "root";           
$DBPass = "";               
$DBName = "emp_db";  

$conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName, 3306);  

if(!$conn){ 
    die("Connection failed: " . mysqli_error($conn));      
}

// Function to check if a string is alphanumeric (no special characters allowed in username)
function isAlphanumeric($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string);
}

// Sign-up functionality for Employee
$signUpErrorMessage = "";
$showSignUpModal = false;  // Flag to control the visibility of the sign-up modal

if (isset($_POST['signUpSubmit'])) { 
    $employeeUser = $_POST['employeeUser'];
    $employeePass = $_POST['employeePass'];
    $employeeEmail = $_POST['employeeEmail'];

    // Server-side validation for username length and alphanumeric characters
    if (strlen($employeeUser) < 8 || !isAlphanumeric($employeeUser)) {
        $signUpErrorMessage = "Error: Username must be at least 8 characters long and contain no special characters.";
        $showSignUpModal = true;
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM tbl_employee WHERE employee_Username = '$employeeUser'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $signUpErrorMessage = "Error: Username already exists.";
            $showSignUpModal = true; // Keep sign-up modal open to allow retry
        } else {
            // Insert new employee into tbl_employee
            $sql = "INSERT INTO tbl_employee (employee_Username, employee_Password, employee_Email) 
                    VALUES ('$employeeUser', '$employeePass', '$employeeEmail')";
            $result = mysqli_query($conn, $sql); 

            if ($result) {
                echo "<p class='error-message'>Sign-up successful!</p>";
            } else {
                $signUpErrorMessage = "Error: Try again. " . mysqli_error($conn);
                $showSignUpModal = true;
            }
        }
    }
}

// Sign-in functionality for Employee
$errorMessage = "";
if (isset($_POST['signInSubmit'])) { 
    $username = $_POST['signInUsername'];
    $password = $_POST['signInPassword'];

    // Server-side validation for username and password length
    if (strlen($username) < 8 || strlen($password) < 8 || !isAlphanumeric($username)) {
        $errorMessage = "Error: Username and password must be at least 8 characters long and alphanumeric.";
    } else {
        // Verify credentials
        $sql = "SELECT * FROM tbl_employee WHERE employee_Username = '$username' AND employee_Password = '$password'";
        $result = mysqli_query($conn, $sql);

        // Check if username and password are valid
        if(mysqli_num_rows($result) == 1) {
            // Redirect to the redirect.php page after successful sign-in
            header("Location: redirect.php");
            exit(); // Ensure no further code is executed after redirection
        } else {
            // Increase failed attempts and show error message
            if (isset($_SESSION['failed_attempts'])) {
                $_SESSION['failed_attempts']++;
            } else {
                $_SESSION['failed_attempts'] = 1;
            }

            // Implement cooldown after 3 failed attempts
            if ($_SESSION['failed_attempts'] >= 3) {
                $_SESSION['cooldown_time'] = time(); // Start cooldown timer
                $cooldownMessage = "Too many failed attempts. Please try again after 10 seconds.";
            } else {
                $errorMessage = "Error: Invalid Username or Password.";
            }
        }
    }
}

// Handle cooldown if necessary
if (isset($_SESSION['cooldown_time'])) {
    $time_since_last_attempt = time() - $_SESSION['cooldown_time'];
    
    if ($time_since_last_attempt < 10) {
        $remaining_time = 10 - $time_since_last_attempt;
        $cooldownMessage = "Too many failed attempts. Please try again after " . $remaining_time . " seconds.";
        // Disable form submission for cooldown period
        echo "<style>#signInModal input, #signInModal button { pointer-events: none; }</style>";
    } else {
        // Clear failed attempts and cooldown time after 10 seconds
        unset($_SESSION['failed_attempts']);
        unset($_SESSION['cooldown_time']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Sign In for Midterms</title>
    <link rel="stylesheet" href="design.css">
    <style>
        /* Error Message Styling */
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 1em;
            text-align: center;
        }
        .input-error {
            border: 2px solid red;
        }
        .error-text {
            color: red;
            font-size: 0.9em;
        }
        .cooldown-message {
            color: orange;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    
    <!-- Home Button -->
    <a href="register.php" id="homeButton" class="home-button">Home</a>

    <!-- Sign In Modal -->
    <div id="signInModal" class="modal <?= (!isset($showSignUpModal) || !$showSignUpModal) ? 'show' : ''; ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Sign In</h1>
            </div>
            <div class="modal-body">
                <form method="POST" action="" onsubmit="return validateSignInForm()">
                    <label for="signInUsername">Username</label>
                    <input type="text" name="signInUsername" id="signInUsername" placeholder="Username" required minlength="8"><br>
                    <span id="signInUsernameError" class="error-text" style="display:none;"></span><br>

                    <label for="signInPassword">Password</label>
                    <input type="password" name="signInPassword" id="signInPassword" placeholder="Password" required minlength="8"><br>
                    <span id="signInPasswordError" class="error-text" style="display:none;"></span><br>

                    <p><a href="forgot-password.php" class="forgot-password">Forgot Password?</a></p>

                    <button type="submit" name="signInSubmit">Sign In</button>
                </form>

                <!-- Error Message (Display when login fails) -->
                <?php if (!empty($errorMessage)) { ?>
                    <p class="error-message"><?= $errorMessage ?></p>
                <?php } ?>

                <!-- Link to Sign Up Modal -->
                <p>Don't have an account? <a href="javascript:void(0);" onclick="openSignUpModal()">Sign Up</a></p>

                <!-- Cooldown Message -->
                <?php if (isset($cooldownMessage)) { ?>
                    <p class="cooldown-message"><?= $cooldownMessage ?></p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Sign Up Modal -->
    <div id="signUpModal" class="modal <?= ($showSignUpModal) ? 'show' : ''; ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Sign Up</h1>
            </div>
            <div class="modal-body">
                <form method="POST" action="" onsubmit="return validateSignUpForm()">
                    <label for="employeeUser">Username</label>
                    <input type="text" name="employeeUser" id="employeeUser" placeholder="Username" required minlength="8"><br>
                    <span id="employeeUserError" class="error-text" style="display:none;"></span><br>

                    <label for="employeeEmail">E-mail</label>
                    <input type="email" name="employeeEmail" id="employeeEmail" placeholder="E-mail" required><br><br>

                    <label for="employeePass">Password</label>
                    <input type="password" name="employeePass" id="employeePass" placeholder="Password" required minlength="8"><br>
                    <span id="employeePassError" class="error-text" style="display:none;"></span><br>

                    <button type="submit" name="signUpSubmit">Sign Up</button>
                </form>

                <!-- Error Message (Display when sign-up fails) -->
                <?php if (!empty($signUpErrorMessage) && $showSignUpModal) { ?>
                    <p class="error-message"><?= $signUpErrorMessage ?></p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Loading Screen -->
    <div id="loadingScreen" class="loading-screen" style="display: none;">
        <p>Loading...</p>
    </div>
    
    <script>
        // Function to open the sign-up modal
        function openSignUpModal() {
            document.getElementById("signInModal").classList.remove("show");
            document.getElementById("signUpModal").classList.add("show");
        }
    </script>
</body>
</html>
