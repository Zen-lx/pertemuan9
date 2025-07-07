<?php
require 'config.php';
require 'header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = mysqli_query($conn, $query);
$mhs = mysqli_fetch_assoc($result);

if (!$mhs) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $query = "UPDATE mahasiswa SET 
              nama = '$nama', 
              jenis_kelamin = '$jenis_kelamin', 
              nomor_hp = '$nomor_hp', 
              alamat = '$alamat' 
              WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<h2>Edit Data Mahasiswa</h2>
<form method="post">
    <div>
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($mhs['nama']) ?>" required>
    </div>
    <div>
        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="Laki-laki" <?= $mhs['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $mhs['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            <option value="Male" <?= $mhs['jenis_kelamin'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $mhs['jenis_kelamin'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
    </div>
    <div>
        <label for="nomor_hp">Nomor HP:</label>
        <input type="text" id="nomor_hp" name="nomor_hp" value="<?= htmlspecialchars($mhs['nomor_hp']) ?>" required>
    </div>
    <div>
        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($mhs['alamat']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php" class="btn btn-danger">Batal</a>
</form>

<?php require 'footer.php'; ?>