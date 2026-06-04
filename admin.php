<?php
session_start(); // Güvenlik görevlisini çağır

// KONTROL: Eğer kişinin elinde "Giriş Kartı" yoksa (!isset)
if (!isset($_SESSION['admin_giris'])) {
    // Onu kapıdan geri çevir ve giriş sayfasına şutla!
    header("Location: login.php");
    exit;
}
?>
<h1>Tebrikler! Admin Paneline Girdiniz.</h1>
<p>Burası gizli yönetim odasıdır.</p>
<a href="cikis.php">Güvenli Çıkış Yap</a>