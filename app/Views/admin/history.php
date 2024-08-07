<!-- app/Views/index.php -->
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">History Checkin</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="reservasiTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Jumlah Orang</th>
                            <th>Harga</th>
                            <th>Status Order</th>
                            <th>Front Office</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historyReservasi as $reservasi) : ?>
                            <tr>
                                <td><?= $reservasi['nama']; ?></td>
                                <td><?= $reservasi['checkin']; ?></td>
                                <td><?= $reservasi['checkout']; ?></td>
                                <td><?= $reservasi['jml_orang']; ?></td>
                                <td><?= $reservasi['bayar']; ?></td>
                                <td><?= $reservasi['status_order']; ?></td>
                                <td>
                                    <?php
                                    // Ambil instance dari UserModel
                                    $userModel = new \App\Models\UserModel();

                                    // Ambil data front office berdasarkan ID dari reservasi
                                    $frontOffice = $userModel->find($reservasi['front_office']);

                                    // Tampilkan nama front office jika ditemukan
                                    echo $frontOffice ? $frontOffice['nama'] : 'Nama tidak ditemukan';
                                    ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<!-- Menambahkan script JavaScript -->
<script>
    // Membuat tabel menjadi dinamis dengan DataTables
    $(document).ready(function() {
        $('#reservasiTable').DataTable();
    });
</script>
<?= $this->endSection() ?>