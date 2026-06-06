<?php
// GÜVENLİK DUVARI: Giriş yapmayan burayı göremez, login.php'ye şutlanır
session_start();
if (!isset($_SESSION['admin_giris']) || $_SESSION['admin_giris'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aurens Parfüm - Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-5">

<div class="container bg-secondary p-5 rounded shadow text-center" style="max-width: 700px;">
    <h1 class="fw-bold mb-4">👑 Aurens Yönetim Paneli</h1>
    <p class="lead mb-5">Hoş geldiniz! Proje Maratonu yönetim merkezindesiniz.</p>
    
    <div class="row g-3 justify-content-center">
        <div class="col-md-6">
            <!-- Az önce açtığımız ürün ekleme sayfasına giden buton -->
            <a href="urun_ekle.php" class="btn btn-primary btn-lg w-100 p-3 fw-semibold">🛠️ Yeni Parfüm Ekle (4. Hafta)</a>
        </div>
        <div class="col-md-6">
            <!-- Birazdan açacağımız çıkış dosyası -->
            <a href="cikis.php" class="btn btn-danger btn-lg w-100 p-3 fw-semibold">🚪 Güvenli Çıkış Yap</a>
        </div>
    </div>
</div>

</body>
</html>