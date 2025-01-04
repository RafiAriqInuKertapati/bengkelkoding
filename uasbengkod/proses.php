<?php
include 'koneksi.php';

if (isset($_POST['aksi'])) {
    if ($_POST['aksi'] == "add") {
        $namaMhs = $_POST['nama'];
        $nim = $_POST['nim'];
        $ipk = $_POST['ipk'];

        // Hitung SKS maksimal berdasarkan IPK
        $sks = ($ipk < 3) ? 20 : 24;

        // Periksa apakah NIM sudah terdaftar
        $cek = mysqli_query($conn, "SELECT * FROM inputmhs WHERE nim = '$nim'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script type='text/javascript'>alert('NIM Sudah Terdaftar!'); window.location.href='index.php';</script>";
        } else {
            // Insert data mahasiswa ke tabel inputmhs
            $query = "INSERT INTO inputmhs (namaMhs, nim, ipk, sks, mataKuliah) VALUES ('$namaMhs', '$nim', '$ipk', '$sks', NULL)";
            $result = mysqli_query($conn, $query);

            if ($result) {
                header("Location: index.php?pesan=sukses_tambah");
            } else {
                echo "<script type='text/javascript'>alert('Gagal menambahkan data mahasiswa.'); window.location.href='index.php';</script>";
            }
        }
    } elseif ($_POST['aksi'] == "edit") {
        $mhs_id = $_POST['id'];
        $makul_id = $_POST['makul'];

        // Ambil data mata kuliah berdasarkan ID
        $query = "SELECT * FROM jwl_mataKuliah WHERE id = '$makul_id'";
        $result = mysqli_query($conn, $query);

        if ($data = mysqli_fetch_assoc($result)) {
            $mataKuliah = $data['mataKuliah'];
            $sks = $data['sks'];
            $kelp = $data['kelp'];
            $ruangan = $data['ruangan'];

            // Insert ke tabel jwl_mhs
            $query_insert = "INSERT INTO jwl_mhs (mhs_id, mataKuliah, sks, kelp, ruangan) 
                             VALUES ('$mhs_id', '$mataKuliah', '$sks', '$kelp', '$ruangan')";
            $result_insert = mysqli_query($conn, $query_insert);

            if ($result_insert) {
                header("Location: edit.php?ubah=$mhs_id&pesan=sukses_edit");
            } else {
                echo "<script type='text/javascript'>alert('Gagal menyimpan data mata kuliah.'); window.location.href='edit.php?ubah=$mhs_id';</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Data mata kuliah tidak ditemukan.'); window.location.href='edit.php?ubah=$mhs_id';</script>";
        }
    }
} elseif (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus data dari tabel jwl_mhs berdasarkan ID
    $query = "DELETE FROM jwl_mhs WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: index.php?pesan=sukses_hapus");
    } else {
        header("Location: index.php?pesan=gagal_hapus");
    }
}
?>
