<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}



if ($_SESSION['role'] !== 'admin') {
    header("Location: v_dasbord_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna | Perpustakaan</title>
    <link rel="stylesheet" href="../asset/edit_pengguna.css">
</head>

<body>

    <div class="card">

        <h2>Edit Pengguna</h2>
        <p>Form Manajemen Pengguna</p>

        <form action="../controllers/c_pengguna.php?aksi=update" method="POST">

            <!-- ID USER -->
            <input type="hidden" name="id_user" value="<?= $users->id_pengguna ?>">

            <label>Nama Pengguna</label>
            <input type="text"
                name="nama_pengguna"
                value="<?= htmlspecialchars($users->nama_pengguna) ?>"
                required>

            <label>Email</label>
            <input type="email"
                name="email"
                value="<?= htmlspecialchars($users->email) ?>"
                required>

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" required>

                <option value="">-- Pilih --</option>

                <option value="laki-laki"
                    <?= $users->jenis_kelamin == 'laki-laki' ? 'selected' : '' ?>>
                    Laki-laki
                </option>

                <option value="perempuan"
                    <?= $users->jenis_kelamin == 'perempuan' ? 'selected' : '' ?>>
                    Perempuan
                </option>

            </select>

            <label>Tanggal Lahir</label>
            <input type="date"
                name="tanggal_lahir_pengguna"
                value="<?= $users->tanggal_lahir_pengguna ?>"
                required>

            <label>Alamat</label>
            <input type="text"
                name="alamat"
                value="<?= htmlspecialchars($users->alamat) ?>"
                required>

            <label>Password</label>
            <input type="password"
                name="password"
                placeholder="Kosongkan jika tidak diubah">

            <label>Role</label>
            <select name="role" required>

                <option value="">-- Pilih Role --</option>

                <option value="admin"
                    <?= $users->role == 'admin' ? 'selected' : '' ?>>
                    Admin
                </option>

                <option value="user"
                    <?= $users->role == 'user' ? 'selected' : '' ?>>
                    User
                </option>

            </select>

            <br><br>

            <button type="submit">Update Pengguna</button>

        </form>

    </div>

</body>

</html>