<?php
require 'config.php';

$query = "SELECT * FROM mahasiswa";
$mahasiswa = query($query);

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=data_mahasiswa.xls");
?>

<table border="1">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Nomor HP</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        <?php $nomor = 1; ?>
        <?php foreach ($mahasiswa as $mhs): ?>
        <tr>
            <td><?= $nomor++ ?></td>
            <td><?= htmlspecialchars($mhs['nama']) ?></td>
            <td><?= htmlspecialchars($mhs['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($mhs['nomor_hp']) ?></td>
            <td><?= htmlspecialchars($mhs['alamat']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>