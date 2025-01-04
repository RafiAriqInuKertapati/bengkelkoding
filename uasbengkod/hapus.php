<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query1 = "DELETE FROM jwl_mhs WHERE mhs_id = '$id'";
    $query2 = "DELETE FROM inputmhs WHERE id = '$id'";
    $result1 = mysqli_query($conn, $query1);
    $result2 = mysqli_query($conn, $query2);

    if ($result1 && $result2) {
        header("Location: index.php?pesan=sukses_hapus");
    } else {
        header("Location: index.php?pesan=gagal_hapus");
    }
} else {
    header("Location: index.php?pesan=gagal_hapus");
}
?>
