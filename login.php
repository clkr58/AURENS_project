<?php
// 1. ADIM: Güvenlik görevlisini uyandır (Session başlat)
// Bu kod en üstte olmalı, üzerinde boşluk bile olmamalı.
session_start();

// 2. ADIM: Veritabanı bağlantı dosyasını çekiyoruz
include 'baglan.php';

// 3. ADIM: Giriş butonuna basıldı mı kontrol ediyoruz
if (isset($_POST['giris_yap'])) {
    $kadi  = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // Veritabanına soruyoruz: "Bu kullanıcı adı ve şifreye sahip biri var mı?"
    $sorgu = $db->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = ? AND sifre = ?");
    $sorgu->execute([$kadi, $sifre]);
    $kullanici = $sorgu->fetch();

    if ($kullanici) {
        // VIP biletini veriyoruz
        $_SESSION['admin_giris'] = true;
        // Admin paneline (admin.php) yönlendiriyoruz
        header("Location: admin.php");
        exit;
    } else {
        // Bilgiler yanlışsa ekranda hata göstermek için değişken oluşturuyoruz
        $hata_mesaji = "Kullanıcı adı veya şifre hatalı!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurens Admin - Yönetici Girişi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark">AURENS</h2>
        <p class="text-muted">Commit Lab Yönetim Paneli Girişi</p>
    </div>

    <?php if(isset($hata_mesaji)): ?>
        <div class="alert alert-danger text-center"><?php echo $hata_mesaji; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        
        <div class="mb-3">
            <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
            <input type="text" name="kullanici_adi" id="kullanici_adi" class="form-control" placeholder="Kullanıcı adınızı girin" required>
        </div>

        <div class="mb-3">
            <label for="sifre" class="form-label">Şifre</label>
            <input type="password" name="sifre" id="sifre" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" name="giris_yap" class="btn btn-dark">Giriş Yap</button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>