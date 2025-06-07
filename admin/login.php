<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_email'] = $admin['email'];
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - This Time Africa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="text-center mb-4">Admin Login</h4>
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>
          </div>
        </div>
        <p class="text-center mt-3 text-muted">&copy; <?= date("Y"); ?> This Time Africa</p>
      </div>
    </div>
  </div>
</body>
</html>
