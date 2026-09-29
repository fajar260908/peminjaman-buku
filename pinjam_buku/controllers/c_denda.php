<?php

namespace App\Controllers;

use App\Models\DendaModel;
use App\Models\PinjamModel;

class Denda extends BaseController
{
    protected $dendaModel;
    protected $pinjamModel;

    public function __construct()
    {
        $this->dendaModel = new DendaModel();
        $this->pinjamModel = new PinjamModel();
    }

    // Menampilkan semua data denda
    public function index()
    {
        $data = [
            'title' => 'Data Denda',
            'denda' => $this->dendaModel
                ->select('denda.*, pinjam.kode_buku, pengguna.nama_pengguna')
                ->join('pinjam', 'pinjam.id_pinjam = denda.id_pinjam')
                ->join('pengguna', 'pengguna.id_pengguna = pinjam.id_pengguna')
                ->findAll()
        ];

        return view('denda/index', $data);
    }

    // Form tambah denda
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Denda',
            'pinjam' => $this->pinjamModel
                ->where('status', 'dikembalikan')
                ->findAll()
        ];

        return view('denda/tambah', $data);
    }

    // Menyimpan data denda
    public function simpan()
    {
        $this->dendaModel->insert([
            'id_pinjam' => $this->request->getPost('id_pinjam'),
            'tanggal_pengembalian' => $this->request->getPost('tanggal_pengembalian'),
            'jenis_denda' => $this->request->getPost('jenis_denda'),
            'jumlah_hari_keterlambatan' => $this->request->getPost('jumlah_hari_keterlambatan'),
            'status_denda' => $this->request->getPost('status_denda')
        ]);

        return redirect()->to('/denda')
            ->with('success', 'Data denda berhasil ditambahkan.');
    }

    // Form edit
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Denda',
            'denda' => $this->dendaModel->find($id),
            'pinjam' => $this->pinjamModel->findAll()
        ];

        return view('denda/edit', $data);
    }

    // Update data denda
    public function update($id)
    {
        $this->dendaModel->update($id, [
            'id_pinjam' => $this->request->getPost('id_pinjam'),
            'tanggal_pengembalian' => $this->request->getPost('tanggal_pengembalian'),
            'jenis_denda' => $this->request->getPost('jenis_denda'),
            'jumlah_hari_keterlambatan' => $this->request->getPost('jumlah_hari_keterlambatan'),
            'status_denda' => $this->request->getPost('status_denda')
        ]);

        return redirect()->to('/denda')
            ->with('success', 'Data denda berhasil diperbarui.');
    }

    // Hapus data denda
    public function hapus($id)
    {
        $this->dendaModel->delete($id);

        return redirect()->to('/denda')
            ->with('success', 'Data denda berhasil dihapus.');
    }
}