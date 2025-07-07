<?php
require 'config.php';
require 'header.php';

// Number of entries per page options
$entriesPerPageOptions = [10, 25, 50, 100];
$entriesPerPage = isset($_GET['entries']) && in_array((int)$_GET['entries'], $entriesPerPageOptions) ? (int)$_GET['entries'] : 10;

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $entriesPerPage;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = $search ? "WHERE nama LIKE '%$search%' OR alamat LIKE '%$search%' OR nomor_hp LIKE '%$search%'" : '';

// Get total entries
$totalQuery = "SELECT COUNT(*) as total FROM mahasiswa $searchCondition";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalEntries = $totalRow['total'];
$totalPages = ceil($totalEntries / $entriesPerPage);

// Get data for current page
$query = "SELECT * FROM mahasiswa $searchCondition LIMIT $offset, $entriesPerPage";
$mahasiswa = query($query);
?>

<div class="control-panel">
    <div class="add-print">
        <a href="create.php" class="btn btn-primary">Tambah Data</a>
        <a href="cetak.php" class="btn btn-primary">Cetak Data</a>
    </div>
    <div class="search-box">
        <form action="" method="get">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
            <input type="hidden" name="page" value="1">
            <input type="hidden" name="entries" value="<?= $entriesPerPage ?>">
        </form>
    </div>
</div>

<div class="entries-per-page">
    <label for="entries">Show:</label>
    <select id="entries" name="entries" onchange="this.form.submit()">
        <?php foreach ($entriesPerPageOptions as $option): ?>
            <option value="<?= $option ?>" <?= ($entriesPerPage === $option) ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
    </select>
    <span>entries</span>
    <form id="entriesForm" action="" method="get">
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <noscript><button type="submit">Go</button></noscript>
    </form>
    <script>
        document.getElementById('entries').addEventListener('change', function() {
            document.getElementById('entriesForm').elements['entries'].value = this.value;
            document.getElementById('entriesForm').submit();
        });
    </script>
</div>

<table>
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Nomor HP</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $nomor = $offset + 1; ?>
        <?php foreach ($mahasiswa as $mhs): ?>
        <tr>
            <td><?= $nomor++ ?></td>
            <td><?= htmlspecialchars($mhs['nama']) ?></td>
            <td><?= htmlspecialchars($mhs['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($mhs['nomor_hp']) ?></td>
            <td><?= htmlspecialchars($mhs['alamat']) ?></td>
            <td>
                <a href="update.php?id=<?= $mhs['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="delete.php?id=<?= $mhs['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <div>
        Showing <?= $offset + 1 ?> to <?= min($offset + $entriesPerPage, $totalEntries) ?> of <?= $totalEntries ?> entries
    </div>
    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&entries=<?= $entriesPerPage ?>&search=<?= urlencode($search) ?>">Previous</a>
        <?php endif; ?>

        <?php
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);

        if ($totalPages <= 5) {
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i === $page) {
                    echo "<span>$i</span>";
                } else {
                    echo "<a href=\"?page=$i&entries=$entriesPerPage&search=" . urlencode($search) . "\">$i</a>";
                }
            }
        } else {
            if ($startPage > 1) {
                echo "<a href=\"?page=1&entries=$entriesPerPage&search=" . urlencode($search) . "\">1</a> ... ";
            }
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i === $page) {
                    echo "<span>$i</span>";
                } else {
                    echo "<a href=\"?page=$i&entries=$entriesPerPage&search=" . urlencode($search) . "\">$i</a>";
                }
            }
            if ($endPage < $totalPages) {
                echo " ... <a href=\"?page=$totalPages&entries=$entriesPerPage&search=" . urlencode($search) . "\">$totalPages</a>";
            }
        }
        ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&entries=<?= $entriesPerPage ?>&search=<?= urlencode($search) ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>

<style>
.control-panel {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.add-print {
    display: flex;
    gap: 10px;
}

.search-box {
    display: flex;
    align-items: center;
}

.search-box label {
    margin-right: 5px;
}

.entries-per-page {
    margin-bottom: 15px;
}

.entries-per-page label,
.entries-per-page select,
.entries-per-page span {
    margin-right: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination a,
.pagination span {
    padding: 8px 12px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin-right: 5px;
    background-color: #fff;
}

.pagination a:hover {
    background-color: #f9f9f9;
}

.pagination span {
    background-color: #f0f0f0;
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 4px;
    user-select: none;
}

.btn-primary {
    color: #fff;
    background-color:rgb(51, 183, 69);
    border-color: #2e6da4;
}

.btn-primary:hover {
    color: #fff;
    background-color: #286090;
    border-color: #204d74;
}

.btn-edit {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
}

.btn-edit:hover {
    color: #fff;
    background-color: #449d44;
    border-color: #398439;
}

.btn-danger {
    color: #fff;
    background-color: #d9534f;
    border-color: #d43f3a;
}

.btn-danger:hover {
    color: #fff;
    background-color: #c9302c;
    border-color: #ac2925;
}
</style>