<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'room';
    protected $primaryKey = 'id';
    protected $allowedFields = ['no_kamar', 'status', 'keterangan'];

    // Metode untuk menyimpan data kamar baru
    public function tambahKamar($data)
    {
        return $this->insert($data);
    }

    // Metode untuk mengambil semua data kamar
    public function semuaKamar()
    {
        return $this->findAll();
    }

    // Metode untuk mengambil data kamar berdasarkan ID
    public function kamarByID($id)
    {
        return $this->find($id);
    }

    // Relasi dengan tabel reservasi
    public function reservasi()
    {
        return $this->hasMany(ReservasiModel::class, 'id');
    }
}
