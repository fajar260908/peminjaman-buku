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

require_once "../models/m_buku.php";

$buku = new m_buku();
$data_buku = $buku->tampil_data();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Buku | Perpustakaan</title>
    <link rel="stylesheet" href="/pinjam_buku/asset/admin_buku.css">
</head>

<body>

<div class="container">

    <div class="navbar">

        <div>
            <h2>📚 Manajemen Buku</h2>
            <p>Kelola data buku sistem perpustakaan</p>
        </div>

        <div class="nav-menu">
            <a href="v_dasbord_admin.php" class="btn btn-user">🏠 Kembali Ke Dashboard</a>
            <a href="v_tambah_buku.php" class="btn btn-tambah">+ Tambah Buku</a>
        </div>

    </div>

    <div class="table-box">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Nama Buku</th>
                    <th>Pencipta</th>
                    <th>Tanggal Rilis</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($data_buku)): ?>
                <?php $no = 1; foreach ($data_buku as $row): ?>

                <tr>

                    <td><?= $no++ ?></td>

                    <td>
                        <?php if (!empty($row->gambar)): ?>
                            <img src="<?= htmlspecialchars($row->gambar) ?>" width="80">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row->nama_buku) ?></td>

                    <td><?= htmlspecialchars($row->pencipta) ?></td>

                    <td><?= date('d-m-Y', strtotime($row->tanggal_rilis)) ?></td>

                    <!-- STOK -->
                    <td>
                        <?php if ($row->stok > 0): ?>
                            <span style="color:green; font-weight:bold;">
                                <?= $row->stok ?>
                            </span>
                        <?php else: ?>
                            <span style="color:red; font-weight:bold;">
                                Habis
                            </span>
                        <?php endif; ?>
                    </td>

                    <td>

                        <a class="btn btn-edit"
                           href="../controllers/c_buku.php?aksi=edit&kode_buku=<?= $row->kode_buku ?>">
                           Edit
                        </a>

                        <a class="btn btn-hapus"
                           onclick="return confirm('Yakin hapus buku ini?')"
                           href="../controllers/c_buku.php?aksi=hapus&kode_buku=<?= $row->kode_buku ?>">
                           Hapus
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>
            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        Data buku belum ada
                    </td>
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