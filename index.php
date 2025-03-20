<?php
// koneksi.php
$host = "localhost";
$user = "root";
$password = "";
$database = "adishop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .filter-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .produk-item {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Daftar Produk</h2>
    <div class="filter-section mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="filterKategori" class="form-label">Filter Kategori:</label>
                <select id="filterKategori" class="form-select" onchange="filterProduk()">
                    <option value="">Semua Kategori</option>
                    <?php
                    $kategoriQuery = "SELECT DISTINCT kategori FROM produk";
                    $result = $conn->query($kategoriQuery);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['kategori'] . "'>" . $row['kategori'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="minHarga" class="form-label">Harga Minimum:</label>
                <input type="number" id="minHarga" class="form-control" placeholder="Masukkan harga minimum" oninput="filterProduk()">
            </div>
            <div class="col-md-4">
                <label for="maxHarga" class="form-label">Harga Maksimum:</label>
                <input type="number" id="maxHarga" class="form-control" placeholder="Masukkan harga maksimum" oninput="filterProduk()">
            </div>
        </div>
    </div>
    <div class="row" id="produkContainer">
        <?php
        $sql = "SELECT * FROM produk";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4 produk-item' data-kategori='" . $row['kategori'] . "' data-harga='" . $row['harga'] . "'>";
                echo "<div class='card'>";
                echo "<img src='" . $row['gambar'] . "' class='card-img-top' alt='Gambar Produk'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . $row['nama_produk'] . "</h5>";
                echo "<p class='card-text'>" . $row['deskripsi'] . "</p>";
                echo "<p><strong>Harga:</strong> Rp" . number_format($row['harga'], 0, ',', '.') . "</p>";
                echo "<p><strong>Stok:</strong> " . $row['stok'] . "</p>";
                echo "<p><strong>Kategori:</strong> " . $row['kategori'] . "</p>";
                echo "</div></div></div>";
            }
        } else {
            echo "<p class='text-center'>Tidak ada produk tersedia.</p>";
        }
        ?>
    </div>
</div>
<script>
    function filterProduk() {
        let kategori = document.getElementById("filterKategori").value;
        let minHarga = parseFloat(document.getElementById("minHarga").value) || 0;
        let maxHarga = parseFloat(document.getElementById("maxHarga").value) || Infinity;
        let produkItems = document.querySelectorAll(".produk-item");

        produkItems.forEach(item => {
            let itemKategori = item.getAttribute("data-kategori");
            let itemHarga = parseFloat(item.getAttribute("data-harga"));

            if (
                (kategori === "" || itemKategori === kategori) &&
                itemHarga >= minHarga &&
                itemHarga <= maxHarga
            ) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>