<?php
session_start();
include 'db_connection.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header("Location: index.php");
        exit;
    }
}

// Login gagal
echo "<script>
    alert('Email atau password salah!');
    window.location.href = 'login.php';
</script>";
exit;

mysqli_close($conn);
?>
