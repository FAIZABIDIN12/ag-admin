<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tgl',
        'kode_order',
        'checkin',
        'nama',
        'no_hp',
        'tgl_checkin',
        'tgl_checkout',
        'jml_orang',
        'jml_kamar',
        'rate',
        'bayar',
        'kurang_bayar',
        'metode_bayar',
        'keterangan',
        'status_bayar',
        'status_order',
        'front_office'
    ];

    public function getLastId()
    {
        // Ambil ID terakhir dari tabel reservasi
        $lastId = $this->select('id')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($lastId) {
            return $lastId['id'];
        } else {
            // Jika tabel kosong, kembalikan 0
            return 0;
        }
    }

    public function getUpcomingReservations()
    {
        // Ambil tanggal hari ini
        $today = date('Y-m-d');

        // Ambil tanggal 3 hari ke depan dari sekarang
        $threeDaysAhead = new \DateTime();
        $threeDaysAhead->modify('+3 days');
        $threeDaysAhead = $threeDaysAhead->format('Y-m-d');

        // Ambil data reservasi dengan tanggal check-in dalam rentang dari hari ini sampai 3 hari ke depan
        return $this->where('tgl_checkin >=', $today)
            ->where('tgl_checkin <=', $threeDaysAhead)->where('status_order', 'booking')
            ->findAll();
    }

    public function getUniqueKodeOrder()
    {
        $db = \Config\Database::connect();

        $sql = "SELECT kode_order FROM reservation
                UNION
                SELECT kode_order FROM checkin
                ORDER BY kode_order";

        $query = $db->query($sql);
        return $query->getResultArray();
    }

    public function generateNewKodeOrder()
    {
        $orders = $this->getUniqueKodeOrder();
        if (empty($orders)) {
            return 'AG-0001';
        }
        $lastOrder = end($orders)['kode_order'];
        $lastOrderNumber = (int) substr($lastOrder, 4);
        $newOrderNumber = $lastOrderNumber + 1;
        $newKodeOrder = 'AG-' . str_pad($newOrderNumber, 4, '0', STR_PAD_LEFT);

        return $newKodeOrder;
    }
}
