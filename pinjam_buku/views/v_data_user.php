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

require_once "../models/m_pengguna.php";

$pengguna = new m_pengguna();
$data_pengguna = $pengguna->tampil_data();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pengguna | Admin</title>
<link rel="stylesheet" href="/pinjam_buku/asset/data_user.css">
</head>

<body>

    <div class="container">

        <div class="top-bar">
            <h2>📚 Manajemen Data Pengguna</h2>
            <p>Kelola seluruh data pengguna sistem perpustakaan</p>

            <div class="menu">

                <div class="btn-group">
                    <a href="v_dasbord_admin.php" class="btn dashboard">🏠 Kembali ke Dashboard</a>
                </div>

                <div class="btn-group">
                    <a href="../controllers/c_pengguna.php?aksi=tambah" class="btn tambah">+ Tambah</a>
                </div>

            </div>
        </div>


        <div class="table-box">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Tanggal Lahir</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($data_pengguna)) : ?>
                        <?php $no = 1; ?>

                        <?php foreach ($data_pengguna as $row) : ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td><?= htmlspecialchars($row->nama_pengguna); ?></td>

                                <td><?= htmlspecialchars($row->email); ?></td>

                                <td style="font-size:11px; max-width:220px; word-break:break-all;">
                                    <?= htmlspecialchars($row->password); ?>
                                </td>

                                <td>
                                    <?= !empty($row->tanggal_lahir_pengguna)
                                        ? date('d-m-Y', strtotime($row->tanggal_lahir_pengguna))
                                        : '-'; ?>
                                </td>

                                <td><?= ucfirst($row->role); ?></td>

                                <td class="aksi">

                                    <a href="../controllers/c_pengguna.php?aksi=edit&id=<?= $row->id_pengguna; ?>" class="edit">Edit</a>

                                    <a href="../controllers/c_pengguna.php?aksi=hapus&id=<?= $row->id_pengguna; ?>"
                                        class="hapus"
                                        onclick="return confirm('Yakin ingin menghapus pengguna ini?');">
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>
                            <td colspan="7">Data pengguna belum ada</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="footer">
            © <?= date('Y'); ?> Sistem Perpustakaan
        </div>

    </div>

</body>

</html>