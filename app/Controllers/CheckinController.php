<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CheckinModel;
use App\Models\UserModel;
use App\Models\KasModel;
use App\Models\FinanceModel;
use App\Models\ReservationModel;
use App\Models\RoomModel;
use App\Services\GenerateOrderCode;
use Dompdf\Dompdf;

class CheckinController extends BaseController
{
    public function index()
    {
        //
    }

    public function simpan_checkin()
    {
        date_default_timezone_set('Asia/Jakarta');

        $idKamar = $this->request->getPost('id_kamar');
        $nama = $this->request->getPost('nama');
        $noHp = $this->request->getPost('no_hp');
        $tglCheckout = $this->request->getPost('checkout_plan');
        $tgl_checkout = \DateTime::createFromFormat('d/m/Y H.i', $tglCheckout)->format('Y-m-d H:i:s');
        $jumlahOrang = $this->request->getPost('jumlah_orang');
        $rate = $this->request->getPost('rate'); // Ambil nilai rate dari form
        $bayar = $this->request->getPost('bayar');
        $metodeBayar = $this->request->getPost('metode_bayar');
        $keterangan = $this->request->getPost('keterangan');

        $userData = session()->get('username');

        $userModel = new UserModel();
        $user = $userModel->where('username', $userData)->first();
        $frontOffice = $user['id'];

        $reservationModel = new ReservationModel();


        $kodeOrder = $this->request->getPost('kode_order');

        if ($kodeOrder === null) {
            $kodeOrder = GenerateOrderCode::generateOrderId();
        } else {
            $order = $reservationModel->where('kode_order', $kodeOrder)->first();
            if ($order) {
                $reservationModel->set('status_order', 'checkin')->where('kode_order', $kodeOrder)->update();
            }
        }

        $roomModel = new RoomModel();
        $kamar = $roomModel->where('id', $idKamar)->first();

        $data = [
            'nama' => $nama,
            'kode_order' => $kodeOrder,
            'no_hp' => $noHp,
            'checkin' => date("Y-m-d H:i:s"),
            'checkout_plan' => $tgl_checkout,
            'jml_orang' => $jumlahOrang,
            'id_room' => $idKamar,
            'rate' => str_replace('.', '', $rate),
            'bayar' => str_replace('.', '', $bayar),
            'metode_bayar' => $metodeBayar,
            'keterangan' => $keterangan,
            'status_order' => 'checkin',
            'front_office' => $frontOffice
        ];

        $dataFinance = [
            'tanggal' => date("Y-m-d H:i:s"),
            'keterangan'   => 'Kamar No. ' . $kamar['no_kamar'] . ' ' . $nama,
            'jenis'   => 'cr',
            'kategori'   => 'checkin',
            'nominal' => str_replace('.', '', $bayar),
            'front_office' => $frontOffice
        ];

        // Menyimpan data rate ke dalam tabel kas masuk
        $kasModel = new KasModel();
        $kasModel->insert([
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'uraian' => 'Rate untuk check-in oleh ' . $nama, // Menambahkan informasi bahwa ini adalah rate
            'kas_masuk' => str_replace('.', '', $rate),
        ]);

        $financeModel = new FinanceModel();
        $financeModel->save($dataFinance);

        // Masukkan data pembayaran ke dalam tabel 'kas_masuk'
        $kasModel->insert([
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'uraian' => 'Pembayaran check-in oleh ' . $nama,
            'kas_masuk' => str_replace('.', '', $bayar),
        ]);

        $checkinModel = new CheckinModel();
        $checkinModel->addCheckinData($data);

        session()->setFlashdata('success', 'Data checkin berhasil disimpan.');
        return redirect()->to(base_url('admin'));
    }



    public function checkout($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $checkinModel = new CheckinModel();
        $kodeOrder = $checkinModel->select('kode_order')->find($id);
        $updated = $checkinModel->update($id, ['status_order' => 'done', 'checkout' => date("Y-m-d H:i:s")]);

        $reservationModel = new ReservationModel();
        $order = $reservationModel->where('kode_order', $kodeOrder)->first();

        if ($order) {
            $reservationModel->set('status_order', 'done')->where('kode_order', $kodeOrder)->update();
        }

        if ($updated) {
            return redirect()->to(base_url('admin'))->with('success', 'Berhasil Checkout');
        } else {
            return redirect()->to(base_url('admin'))->with('error', 'Gagal checkout');
        }
    }

    public function history()
    {
        // Membuat instance dari model ReservasiModel
        $checkinModel = new CheckinModel();

        // Mengambil data history reservasi dari model
        $historyReservasi = $checkinModel->findAll();

        // Mengirim data history reservasi ke tampilan history.php
        return view('admin/history', ['historyReservasi' => $historyReservasi]);
    }

    public function detailCheckin($id)
    {

        $checkinModel = new CheckinModel();
        $detailCheckin = $checkinModel->getKamarById($id);

        if ($detailCheckin) {
            // Jika data ditemukan, kirim respons JSON
            return $this->response->setJSON($detailCheckin);
        } else {
            // Jika data tidak ditemukan, kirim respons JSON dengan pesan error
            return $this->response->setJSON(['error' => 'Data not found'])->setStatusCode(404);
        }
    }

    public function printCheckin($checkinId)
    {
        // Inisialisasi model CheckinModel
        $checkinModel = new CheckinModel();

        // Lakukan proses pengambilan data checkin berdasarkan ID checkin
        $checkin = $checkinModel->find($checkinId);

        if (!$checkin) {
            // Jika data checkin tidak ditemukan, redirect atau tampilkan pesan error
            return redirect()->to('/admin/checkin')->with('error', 'Data checkin tidak ditemukan.');
        }

        // Load view untuk mencetak nota checkin dengan data yang telah diambil
        $html = view('admin/print_checkin', ['checkin' => $checkin]);

        // Inisialisasi objek Dompdf
        $dompdf = new Dompdf();

        // Load HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Render PDF
        $dompdf->render();

        // Simpan PDF ke file atau tampilkan di browser
        $dompdf->stream('nota_checkin.pdf');
    }
}
