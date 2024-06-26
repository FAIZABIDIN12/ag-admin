<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CheckinModel;

class ReportController extends BaseController
{
    public function index()
    {
        $checkinModel = new CheckinModel();
        $allData = $checkinModel->findAll();

        $dataPerTanggal = [];

        // Iterasi melalui data yang diperoleh dari database
        foreach ($allData as $data) {
            // Ambil tanggal checkout dari setiap entri
            $tanggalCheckout = date("Y-m-d", strtotime($data['checkin']));

            // Jika tanggal tersebut belum ada dalam array $dataPerTanggal, inisialisasi array untuk tanggal tersebut
            if (!isset($dataPerTanggal[$tanggalCheckout])) {
                $dataPerTanggal[$tanggalCheckout] = [
                    'kamar_terpakai' => 0,
                    'harga' => 0,
                    'terbayar' => 0
                ];
            }

            // Increment jumlah kamar terpakai untuk tanggal tersebut
            $dataPerTanggal[$tanggalCheckout]['kamar_terpakai']++;

            // Tambahkan harga dan terbayar untuk tanggal tersebut
            $dataPerTanggal[$tanggalCheckout]['harga'] += $data['rate'];
            $dataPerTanggal[$tanggalCheckout]['terbayar'] += $data['bayar'];
        }

        $data['reports'] = $dataPerTanggal;

        return view('admin/report/index', $data);
    }

    public function filterByMonth()
    {
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        // Konversi bulan menjadi dua digit jika kurang dari 10
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $checkinModel = new CheckinModel();
        $filteredData = $checkinModel->filterByMonthYear($bulan, $tahun);

        $dataPerTanggal = [];

        // Iterasi melalui data yang diperoleh dari filter
        foreach ($filteredData as $data) {
            // Ambil tanggal checkout dari setiap entri
            $tanggalCheckout = date("Y-m-d", strtotime($data['checkout']));

            // Jika tanggal tersebut belum ada dalam array $dataPerTanggal, inisialisasi array untuk tanggal tersebut
            if (!isset($dataPerTanggal[$tanggalCheckout])) {
                $dataPerTanggal[$tanggalCheckout] = [
                    'kamar_terpakai' => 0,
                    'harga' => 0,
                    'terbayar' => 0
                ];
            }

            // Increment jumlah kamar terpakai untuk tanggal tersebut
            $dataPerTanggal[$tanggalCheckout]['kamar_terpakai']++;

            // Tambahkan harga dan terbayar untuk tanggal tersebut
            $dataPerTanggal[$tanggalCheckout]['harga'] += $data['rate'];
            $dataPerTanggal[$tanggalCheckout]['terbayar'] += $data['bayar'];
        }

        $data['reports'] = $dataPerTanggal;

        // Tampilkan view dengan data laporan yang difilter
        return view('admin/report/index', $data);
    }
}
