<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>
<link rel="stylesheet" href="/pinjam_buku/asset/login.css">
</head>

<body>

    <div class="login-card">

        <h2>📚 Perpustakaan</h2>
        <p>Sistem Peminjaman Buku</p>

        <form method="POST" action="/pinjam_buku/controllers/c_login.php">

            <input type="text" name="username_email" placeholder="Username atau Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="login">Masuk</button>

        </form>

        <div class="register-link">
            Belum punya akun?
            <a href="/pinjam_buku/views/v_form_pendaftaran.php">Daftar di sini</a>
        </div>

        <div class="footer">
            © <?= date('Y'); ?> Perpustakaan
        </div>

    </div>

</body>

</html>