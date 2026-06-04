<?php
session_start();
session_destroy(); // Verilen giriş kartını imha et (Tüm oturumu sil)
header("Location: login.php"); // Giriş sayfasına geri dön
exit;
?>