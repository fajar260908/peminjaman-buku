<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            padding: 5px 10px;
        }
    </style>
</head>

<body>

<h2><?= $title ?></h2>

<a href="<?= base_url('denda/tambah') ?>">
    + Tambah Denda
</a>

<br><br>

<?php if (session()->getFlashdata('success')) : ?>
    <p>
        <?= session()->getFlashdata('success') ?>
    </p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Pinjam</th>
            <th>Kode Buku</th>
            <th>Nama Pengguna</th>
            <th>Tanggal Pengembalian</th>
            <th>Jenis Denda</th>
            <th>Jumlah Hari Keterlambatan</th>
            <th>Status Denda</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php $no = 1; ?>

    <?php foreach ($denda as $d) : ?>

        <tr>
            <td><?= $no++ ?></td>

            <td><?= $d['id_pinjam'] ?></td>

            <td><?= $d['kode_buku'] ?></td>

            <td><?= $d['nama_pengguna'] ?></td>

            <td><?= $d['tanggal_pengembalian'] ?></td>

            <td><?= $d['jenis_denda'] ?></td>

            <td><?= $d['jumlah_hari_keterlambatan'] ?> hari</td>

            <td><?= $d['status_denda'] ?></td>

            <td>
                <a href="<?= base_url('denda/edit/' . $d['id_denda']) ?>">
                    Edit
                </a>

                <a href="<?= base_url('denda/hapus/' . $d['id_denda']) ?>"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                </a>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>

</body>
</html>