<?php
session_start();
session_destroy(); // Bilgisayardaki giriş biletini yırtıp atar
header("Location: login.php"); // Giriş ekranına geri yönlendirir
exit;
