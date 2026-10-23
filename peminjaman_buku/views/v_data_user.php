<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
if (!isset($data_pengguna)) { require_once __DIR__ . '/../models/m_pengguna.php'; $data_pengguna = (new m_pengguna())->tampil_data(); }
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pengguna | Admin</title>
<link rel="stylesheet" href="<?= h(app_url('asset/data_user.css')) ?>">
<link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>">
</head>

<body>

    <div class="container">

        <div class="top-bar">
            <h2>📚 Manajemen Data Pengguna</h2>
            <p>Kelola seluruh data pengguna sistem perpustakaan</p>

            <div class="menu">

                <div class="btn-group">
                    <a href="<?= h(app_url('views/v_dasbord_admin.php')) ?>" class="btn dashboard">🏠 Kembali ke Dashboard</a>
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
<td>
                                    <?= !empty($row->tanggal_lahir_pengguna)
                                        ? date('d-m-Y', strtotime($row->tanggal_lahir_pengguna))
                                        : '-'; ?>
                                </td>

                                <td><?= ucfirst($row->role); ?></td>

                                <td class="aksi">

                                    <a href="../controllers/c_pengguna.php?aksi=edit&id=<?= $row->id_pengguna; ?>" class="edit">Edit</a>

                                    <form method="post" action="../controllers/c_pengguna.php?aksi=hapus" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                        <input type="hidden" name="id" value="<?= (int)$row->id_pengguna; ?>">
                                        <button type="submit" class="hapus btn btn-small btn-danger">Hapus</button>
                                    <?= csrf_field() ?></form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>
                            <td colspan="6">Data pengguna belum ada</td>
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