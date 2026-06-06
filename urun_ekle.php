<?php
// 1. GÜVENLİK KAPISI: Sadece giriş yapmış olan adminler görebilir
session_start();
if (!isset($_SESSION['admin_giris']) || $_SESSION['admin_giris'] !== true) {
    header("Location: login.php");
    exit;
}

// 2. BAĞLANTI: Veritabanı köprüsü
include 'baglan.php';

// Form gönderildi mi kontrol ediyoruz
if (isset($_POST['urun_kaydet'])) {
    // Formdan gelen veriler (Tablo sütun isimlerine uygun değişkenler)
    $urun_adi = $_POST['urun_adi'];
    $urun_fiyat = $_POST['urun_fiyat'];
    $urun_aciklama = $_POST['urun_aciklama'];
    
    // 3. MEDYA YÖNETİMİ: Resim yükleme
    $gelen_gorsel = $_FILES['gorsel'];
    
    if ($gelen_gorsel['error'] == 0) {
        $dosya_adi = $gelen_gorsel['name'];
        $gecici_yol = $gelen_gorsel['tmp_name'];
        
        // [CHALLENGE]: Güvenlik kontrolü
        $uzanti = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));
        $izin_verilenler = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($uzanti, $izin_verilenler)) {
            $hata_mesaji = "Geçersiz dosya türü! Sadece JPG, JPEG ve PNG.";
        } else {
            // [CHALLENGE]: Benzersiz isim üretme (time fonksiyonu ile)
            $yeni_dosya_adi = time() . "_" . $dosya_adi;
            
            // ORTAK KLASÖR YOLUMUZ: Gerçek yer olan images/urunler yapıldı!
            $hedef_yol = "images/urunler/" . $yeni_dosya_adi;
            
            if (move_uploaded_file($gecici_yol, $hedef_yol)) {
                
                // 4. VERİTABANINA YAZMA: Sütun isimleri Betül'ün veritabanıyla tam eşleşti!
                $sorgu = $db->prepare("INSERT INTO urunler (urun_adi, urun_fiyat, urun_aciklama, urun_gorsel) VALUES (?, ?, ?, ?)");
                $ekle = $sorgu->execute([$urun_adi, $urun_fiyat, $urun_aciklama, $yeni_dosya_adi]);
                
                if ($ekle) {
                    $basari_mesaji = "Ürün ve Görsel Başarıyla Eklendi! 🎉";
                } else {
                    $hata_mesaji = "Veritabanına kaydedilirken bir SQL hatası oluştu.";
                }
                
            } else {
                $hata_mesaji = "Resim klasöre taşınamadı. 'images/urunler/' klasörünün varlığını kontrol edin.";
            }
        }
    } else {
        $hata_mesaji = "Lütfen geçerli bir ürün görseli seçin!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aurens Panel - Üretim Bandı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container bg-white p-5 shadow rounded" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">🛠️ Üretim Bandı</h3>
        <a href="admin.php" class="btn btn-sm btn-secondary">Panele Dön</a>
    </div>

    <?php if(isset($basari_mesaji)): ?>
        <div class="alert alert-success"><?php echo $basari_mesaji; ?></div>
    <?php endif; ?>
    <?php if(isset($hata_mesaji)): ?>
        <div class="alert alert-danger"><?php echo $hata_mesaji; ?></div>
    <?php endif; ?>

    <form action="urun_ekle.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label class="form-label fw-semibold">Parfüm Adı</label>
            <input type="text" name="urun_adi" class="form-control" placeholder="Örn: Aurens Gold" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Fiyat (TL)</label>
            <input type="number" step="0.01" name="urun_fiyat" class="form-control" placeholder="Örn: 450.00" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Açıklama</label>
            <textarea name="urun_aciklama" class="form-control" rows="3" placeholder="Parfüm notalarını yazın..." required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Ürün Fotoğrafı</label>
            <input type="file" name="gorsel" class="form-control" required>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" name="urun_kaydet" class="btn btn-primary btn-lg">Ürünü Sisteme Kaydet</button>
        </div>

    </form>
</div>

</body>
</html>