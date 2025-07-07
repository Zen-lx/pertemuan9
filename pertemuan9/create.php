<?php
require 'config.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $query = "INSERT INTO mahasiswa (nama, jenis_kelamin, nomor_hp, alamat) 
              VALUES ('$nama', '$jenis_kelamin', '$nomor_hp', '$alamat')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<h2>Tambah Data Mahasiswa</h2>
<form method="post">
    <div>
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>
    </div>
    <div>
        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
    </div>
    <div>
        <label for="nomor_hp">Nomor HP:</label>
        <input type="text" id="nomor_hp" name="nomor_hp" required>
    </div>
    <div>
        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php" class="btn btn-danger">Batal</a>
</form>

<?php require 'footer.php'; ?>