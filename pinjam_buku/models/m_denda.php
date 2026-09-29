<?php

namespace App\Models;

use CodeIgniter\Model;

class DendaModel extends Model
{
    protected $table = 'denda';
    protected $primaryKey = 'id_denda';

    protected $allowedFields = [
        'id_pinjam',
        'tanggal_pengembalian',
        'jenis_denda',
        'jumlah_hari_keterlambatan',
        'status_denda'
    ];
}