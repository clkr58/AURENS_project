<?php 
// 1. GÖREV MADDESİ: Güvenlik görevlisini (Session) en üstte uyandırıyoruz!
session_start(); 

// Geçen hafta oluşturduğumuz veritabanı köprüsünü (baglan.php) sayfaya dahil ediyoruz
include 'baglan.php'; 

// Eğer kullanıcı giriş butonuna bastıysa (Form POST edildiyse) bu blok çalışacak
if (isset($_POST['giris_yap'])) {
    
    // 2. GÖREV MADDESİ: Formdan gelen verileri POST yöntemiyle yakalıyoruz
    $gelen_kullanici = $_POST['kullanici_adi'];
    $gelen_sifre = $_POST['sifre'];

    // 3. GÖREV MADDESİ: PDO kullanarak veritabanına soruyoruz
    // SQL Injection saldırılarından korunmak için güvenli "prepare" yöntemini kullanıyoruz
    $sorgu = $db->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = :kadi AND sifre = :sifre");
    $sorgu->execute([
        'kadi' => $gelen_kullanici,
        'sifre' => $gelen_sifre
    ]);
    
    // Veritabanında eşleşen satır var mı diye bakıyoruz
    $yonetici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($yonetici) {
        // 4. GÖREV MADDESİ: VIP Biletini Kesiyoruz! 
        // Eşleşme varsa session biletini tanımlıyoruz
        $_SESSION['admin_giris'] = true;
        $_SESSION['admin_kullanici'] = $yonetici['kullanici_adi']; // İleride hoş geldin demek için adını da saklayalım

        // Kullanıcıyı kolundan tutup VIP odaya (Admin Paneline) yönlendiriyoruz
        header("Location: admin.php");
        exit; // Yönlendirmeden sonra kodların çalışmasını durduruyoruz
    } else {
        // Eşleşme yoksa ekranda görünecek hata mesajı değişkeni
        $hata_mesaji = "Hatalı Kullanıcı Adı veya Şifre!";
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

    <?php if (isset($hata_mesaji)): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php echo $hata_mesaji; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        
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