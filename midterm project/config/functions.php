<?php
function validatePhone($phone) {
    // Must be 9 digits and start with 78, 77, 73, 71, or 70
    return preg_match('/^(78|77|73|71|70)[0-9]{7}$/', $phone);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>
