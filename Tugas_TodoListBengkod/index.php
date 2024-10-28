<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'todo_list_db');

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk menyimpan data tugas yang akan diubah
$edit_id = null;
$edit_data = [
    'isi' => '',
    'tgl_awal' => '',
    'tgl_akhir' => ''
];

// Cek jika tombol "Ubah" diklik
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tasks WHERE id=$edit_id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}

// Tambah atau ubah 
if (isset($_POST['save_task'])) {
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    if ($edit_id) {
        // Update data
        $sql = "UPDATE tasks SET isi='$isi', tgl_awal='$tgl_awal', tgl_akhir='$tgl_akhir' WHERE id=$edit_id";
    } else {
        // Tambah data
        $sql = "INSERT INTO tasks (isi, tgl_awal, tgl_akhir) VALUES ('$isi', '$tgl_awal', '$tgl_akhir')";
    }
    $conn->query($sql);
    header('Location: index.php');
}

// Ubah status 
if (isset($_GET['change_status'])) {
    $id = $_GET['change_status'];
    $sql = "UPDATE tasks SET status = IF(status='Belum', 'Sudah', 'Belum') WHERE id=$id";
    $conn->query($sql);
    header('Location: index.php');
}

// Hapus
if (isset($_GET['delete_task'])) {
    $id = $_GET['delete_task'];
    $sql = "DELETE FROM tasks WHERE id=$id";
    $conn->query($sql);
    header('Location: index.php');
}

// Ambil data 
$result = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">To Do List</h2>

        <!-- Form tambah/ubah tugas -->
        <form method="POST" class="mb-3">
            <div class="form-group">
                <input type="text" name="isi" class="form-control" placeholder="Kegiatan" value="<?php echo $edit_data['isi']; ?>" required>
            </div>
            <div class="form-group">
                <input type="date" name="tgl_awal" class="form-control" value="<?php echo $edit_data['tgl_awal']; ?>" required>
            </div>
            <div class="form-group">
                <input type="date" name="tgl_akhir" class="form-control" value="<?php echo $edit_data['tgl_akhir']; ?>" required>
            </div>
            <button type="submit" name="save_task" class="btn btn-primary">Simpan</button>
        </form>

        <!-- Tabel -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kegiatan</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $index++; ?></td>
                        <td><?php echo $row['isi']; ?></td>
                        <td><?php echo $row['tgl_awal']; ?></td>
                        <td><?php echo $row['tgl_akhir']; ?></td>
                        <td>
                            <a href="?change_status=<?php echo $row['id']; ?>" class="btn btn-sm <?php echo $row['status'] == 'Belum' ? 'btn-warning' : 'btn-success'; ?>">
                                <?php echo $row['status']; ?>
                            </a>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Ubah</a>
                            <a href="?delete_task=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>