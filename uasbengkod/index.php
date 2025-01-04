<?php
include 'koneksi.php';

// Ambil data mahasiswa beserta mata kuliah yang diambil
$query = "
    SELECT 
        m.id, 
        m.namaMhs, 
        m.nim, 
        m.ipk, 
        m.sks, 
        GROUP_CONCAT(DISTINCT j.mataKuliah SEPARATOR ', ') AS mataKuliah 
    FROM inputmhs m
    LEFT JOIN jwl_mhs j ON m.id = j.mhs_id
    GROUP BY m.id;
";
$sql = mysqli_query($conn, $query);
$no = 0;

// Pesan notifikasi
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script src="js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Input KRS</title>
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <div class="container-md mt-2">
        <div class="card">
            <form method="POST" action="proses.php">
                <div class="text-center">
                    <h1>Sistem Input Kartu Rencana Studi (KRS)</h1>
                    <p>Input Data Mahasiswa Disini!</p>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Masukkan Nama Mahasiswa"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">NIM</label>
                                <input
                                    type="text"
                                    name="nim"
                                    class="form-control"
                                    placeholder="Masukkan NIM"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">IPK</label>
                                <input
                                    type="text"
                                    name="ipk"
                                    class="form-control"
                                    placeholder="Masukkan IPK"
                                    required
                                />
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" name="aksi" value="add" type="submit">
                                Input Mahasiswa
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="container mt-4 text-center">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIM</th>
                            <th scope="col">IPK</th>
                            <th scope="col">SKS Maksimal</th>
                            <th scope="col">Mata Kuliah</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($sql)) { ?>
                            <tr>
                                <th><?php echo ++$no; ?></th>
                                <td><?php echo $result['namaMhs']; ?></td>
                                <td><?php echo $result['nim']; ?></td>
                                <td><?php echo $result['ipk']; ?></td>
                                <td><?php echo $result['sks']; ?></td>
                                <td><?php echo $result['mataKuliah'] ?: '-'; ?></td>
                                <td>
                                    <a href="hapus.php?nim=<?php echo $result['nim']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </a>
                                    <a href="edit.php?ubah=<?php echo $result['id']; ?>" class="btn btn-primary">Edit</a>
                                    <a href="cetak.php?ubah=<?php echo $result['nim']; ?>" class="btn btn-secondary">Lihat</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container">
        <?php if ($pesan == "sukses_tambah") { ?>
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Data berhasil ditambahkan.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php } elseif ($pesan == "sukses_hapus") { ?>
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Data berhasil dihapus.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php } elseif ($pesan == "gagal_hapus") { ?>
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Data gagal dihapus.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php } ?>
    </div>

    <script>
        // Script untuk otomatis menyembunyikan toast setelah beberapa detik    
        document.querySelectorAll('.toast').forEach(toast => {
            const bsToast = new bootstrap.Toast(toast);
            setTimeout(() => bsToast.hide(), 3000); // Hilang setelah 3 detik
        });
    </script>
</body>
</html>
