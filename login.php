<?php
session_start();
require_once 'koneksi.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah pengguna adalah admin
    $sql_admin = "SELECT * FROM akun_admin WHERE username = :username AND password = :password";
    $stmt_admin = $pdo->prepare($sql_admin);
    $stmt_admin->bindParam(':username', $username);
    $stmt_admin->bindParam(':password', $password); // Pastikan password sudah terenkripsi jika diperlukan
    $stmt_admin->execute();
    $admin = $stmt_admin->fetch();

    if ($admin) {
        // Jika pengguna adalah admin
        $_SESSION['user_id'] = $admin['id_admin'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = 'admin';
        
        header("Location: index.php"); // Arahkan ke halaman admin
        exit;
    } else {
        // Jika bukan admin, cek ke database untuk user biasa (nasabah)
        $sql_user = "SELECT * FROM akun_nasabah WHERE nisn = :nisn AND no_rekening = :password";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->bindParam(':nisn', $username);  // Menggunakan NISN sebagai username untuk nasabah
        $stmt_user->bindParam(':password', $password);
        $stmt_user->execute();
        $user = $stmt_user->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id_nasabah'];
            $_SESSION['nisn'] = $user['nisn'];
            $_SESSION['no_rekening'] = $user['no_rekening'];
            $_SESSION['role'] = 'user';

            header("Location: transaksi_user.php"); // Arahkan ke halaman nasabah
            exit;
        } else {
            $error_message = 'Username atau Password salah!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.php" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center">Login</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">NISN</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
