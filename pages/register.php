<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = htmlspecialchars(trim($_POST['name']));
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $password_confirmation = $_POST['password_confirmation'];
  $type = $_POST['type'];

  if (empty($name) || empty($email) || empty($password) || empty($password_confirmation) || empty($type)) {
    $error = "All fields are required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format";
  } elseif ($password !== $password_confirmation) {
    $error = "Passwords do not match";
  } else {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $check_email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();

    if ($check_email_result->num_rows > 0) {
      $error = "Email already exists, Login instead";
    } else {
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $email, $password_hash, $type);

      if ($stmt->execute()) {
        redirect('login.php');
      } else {
        $error = "Error: " . $stmt->error;
      }
    }
  }
}
?>


<div class="auth-container container">
  <div class="auth-header">
    <h2>Register</h2>
    <p>Create your account</p>
  </div>

  <form method="POST" class="auth-form">
    <div class="input-fields">
      <input class="field" type="text" id="name" name="name" placeholder="Full Name" required>
      <input class="field" type="email" id="email" name="email" placeholder="Email Address" required>
      <input class="field" type="password" id="password" name="password" placeholder="Password" required>
      <input class="field" type="password" id="password_confirmation" name="password_confirmation"
        placeholder="Confirm Password" required>
      <select class="field" name="type" required>
        <option value="" disabled selected hidden>Account Type</option>
        <option value="customer">Customer</option>
        <option value="owner">Venue Owner</option>
      </select>
    </div>

    <button type="submit" class="btn-primary">Register</button>
  </form>

  <?php if ($error): ?>
    <p class="error-flash">
      <?= $error ?>
    </p>
  <?php endif; ?>

  <div class="auth-links">
    <p>Already a member? <a href="login.php">Login</a></p>
  </div>

</div>