<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['type'];
            redirect('../pages/dashboard.php');
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
}
?>


<div class="auth-container container">
    <div class="auth-header">
        <h2>Login</h2>
        <p>Welcome back!</p>
    </div>

    <form method="POST" class="auth-form">
        <div class="input-fields">
            <input class="field" type="email" id="email" name="email" placeholder="Email Address" required>
            <input class="field" type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-primary">Login</button>
    </form>

    <?php if ($error): ?>
        <p class="error-flash"><?= $error ?></p>
    <?php endif; ?>

    <div class="auth-links">
        <p>Not a member yet? <a href="register.php">Register</a>
        </p>
    </div>

</div>