<!DOCTYPE html>
<?php 
include 'koneksi.php';

if (isset($_GET['ubah'])) {
    $nim = $_GET['ubah'];

    // Query untuk mengambil data mahasiswa berdasarkan NIM
    $query = "SELECT * FROM inputmhs WHERE nim = '$nim';";
    $query_mhs = "
        SELECT 
            jwl_mhs.mataKuliah, 
            jwl_mhs.sks, 
            jwl_mhs.kelp, 
            jwl_mhs.ruangan 
        FROM jwl_mhs 
        INNER JOIN inputmhs 
        ON jwl_mhs.mhs_id = inputmhs.id 
        WHERE inputmhs.nim = '$nim';
    ";

    // Eksekusi query
    $sql = mysqli_query($conn, $query);
    if (!$sql) {
        die("Error query mahasiswa: " . mysqli_error($conn));
    }

    $sql_mhs = $conn->query($query_mhs);
    if (!$sql_mhs) {
        die("Error query mata kuliah: " . mysqli_error($conn));
    }

    $no = 0;
} else {
    die("NIM tidak ditemukan.");
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script src="js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kartu Rencana Studi</title>
</head>
<body>
    <div class="container-md mt-2">
        <div class="card">
            <div class="text-center">
                <h1>Kartu Rencana Studi</h1>
                <p>Lihat Jadwal Mata Kuliah Yang Telah Diinputkan Di sini!</p>
            </div>

            <?php
            // Tampilkan data mahasiswa
            if (mysqli_num_rows($sql) > 0) {
                $result = mysqli_fetch_assoc($sql);
            ?>
            <div class="container text-center">
                <div class="alert alert-primary" role="alert">
                    <p>
                        <b>Mahasiswa:</b> <?php echo $result['namaMhs']; ?> | 
                        <b>NIM:</b> <?php echo $result['nim']; ?> | 
                        <b>IPK:</b> <?php echo $result['ipk']; ?> | 
                        <b>SKS Maksimal:</b> <?php echo $result['sks']; ?>
                    </p>
                    <a href="index.php" type="button" class="btn btn-warning">Kembali Ke Data Mahasiswa</a>
                </div>
            </div>
            <?php
            } else {
                echo "<p class='text-center text-danger'>Data mahasiswa tidak ditemukan.</p>";
            }
            ?>

            <div class="container mt-4 text-center">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Mata Kuliah</th>
                            <th scope="col">SKS</th>
                            <th scope="col">Kelompok</th>
                            <th scope="col">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan daftar mata kuliah
                        if ($sql_mhs->num_rows > 0) {
                            while ($result = $sql_mhs->fetch_assoc()) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo ++$no; ?></th>
                            <td><?php echo $result['mataKuliah']; ?></td>
                            <td><?php echo $result['sks']; ?></td>
                            <td><?php echo $result['kelp']; ?></td>
                            <td><?php echo $result['ruangan']; ?></td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-danger'>Mata kuliah belum diinputkan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    // Script untuk mencetak halaman secara otomatis saat terbuka
    window.print();
</script>
</html>
