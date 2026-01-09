<?php
session_start();
require_once 'config/config.php';
require_once 'config/functions.php';

$error = '';
$success = '';

function validateUsername($name) {
    $name = trim($name);
    
    if (empty($name)) {
        return "Name is required";
    }
    
    if (strlen($name) < 2) {
        return "Name is too short (minimum 2 characters)";
    }
    
    if (strlen($name) > 50) {
        return "Name is too long (maximum 50 characters)";
    }
    
    if (preg_match('/[0-9]/', $name)) {
        return "Numbers are not allowed in the name";
    }
    
    if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~]/', $name)) {
        return "Special characters are not allowed in the name";
    }
    
    if (!preg_match('/^[\p{Arabic}a-zA-Z]+( [\p{Arabic}a-zA-Z]+)*$/u', $name)) {
        return "Name must contain only Arabic or English letters, with a single space between words";
    }
    
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $phone = sanitize($_POST['phone']);

    $usernameValidation = validateUsername($username);
    if ($usernameValidation !== true) {
        $error = $usernameValidation;
    }
    elseif (empty($email) || empty($password) || empty($phone)) {
        $error = "All fields are required.";
    }
    elseif (!validateEmail($email)) {
        $error = "Invalid email format.";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    }
    elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "Passwords do not match.";
    }
    elseif (!validatePhone($phone)) {
        $error = "Invalid phone number. It must be 9 digits and start with 78, 77, 73, 71, or 70.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $phone]);
            $success = "Registration successful. <a href='login.php'>Login here</a>";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email is already registered.";
            } else {
                $error = "Registration error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Create New Account</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .hint { color: #666; font-size: 12px; margin-top: 5px; }
    </style>
</head>

<body>
    <div class="page">
        <div class="image-section"></div>
        <div class="form-section">
            <div class="container">
                <h2>Create New Account</h2>
                
                <?php if ($error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Full Name" required
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        <div class="hint"></div>
                    </div>
                    
                    <input type="email" name="email" placeholder="Email Address" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    
                    <input type="password" name="password" placeholder="Password (at least 6 characters)" required>
                    
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    
                    <input type="text" name="phone" placeholder="Phone Number (e.g. 771234567)" required
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    
                    <button type="submit">Register</button>
                </form>
                
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
