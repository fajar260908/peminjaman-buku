<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>

<body>

<h2><?= $title ?></h2>

<form action="<?= base_url('denda/simpan') ?>" method="post">

    <label>ID Pinjam</label>
    <br>

    <select name="id_pinjam" required>

        <option value="">-- Pilih Peminjaman --</option>

        <?php foreach ($pinjam as $p) : ?>

            <option value="<?= $p['id_pinjam'] ?>">
                <?= $p['id_pinjam'] ?> - <?= $p['kode_buku'] ?? '' ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Tanggal Pengembalian</label>
    <br>

    <input
        type="date"
        name="tanggal_pengembalian"
        required
    >

    <br><br>

    <label>Jenis Denda</label>
    <br>

    <select name="jenis_denda" required>

        <option value="">
            -- Pilih Jenis Denda --
        </option>

        <option value="Terlambat Mengembalikan Buku">
            Terlambat Mengembalikan Buku
        </option>

        <option value="Kerusakan">
            Kerusakan
        </option>

        <option value="Hilang">
            Hilang
        </option>

    </select>

    <br><br>

    <label>Jumlah Hari Keterlambatan</label>
    <br>

    <input
        type="number"
        name="jumlah_hari_keterlambatan"
        min="0"
        value="0"
        required
    >

    <br><br>

    <label>Status Denda</label>
    <br>

    <select name="status_denda" required>

        <option value="Belum Dibayar">
            Belum Dibayar
        </option>

        <option value="Sudah Dibayar">
            Sudah Dibayar
        </option>

    </select>

    <br><br>

    <button type="submit">
        Simpan
    </button>

    <a href="<?= base_url('denda') ?>">
        Kembali
    </a>

</form>

</body>
</html>