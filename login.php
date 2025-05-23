
<?php
session_start();
include 'db_connection.php';
// Kalau user sudah login, langsung ke index
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Contoh verifikasi (bisa kamu ganti dengan database)
    if ($email === 'admin@example.com' && $password === '123') {
        $_SESSION['username'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!-- HTML tampilan login -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header text-center"><h4>Login</h4></div>
        <div class="card-body">
          <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
          <form method="POST" action="proses_login.php">
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>