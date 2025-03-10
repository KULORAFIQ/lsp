<?php
session_start();

// Validasi CAPTCHA
if ($_POST['captcha'] != $_SESSION['captcha']) {
    // CAPTCHA tidak valid, tampilkan pesan error
    echo "CAPTCHA tidak valid!";
} else {
    // CAPTCHA valid, lakukan aksi sesuai kebutuhan

    // Contoh aksi: Redirect ke halaman lain
    header("Location: form_admin.php");
    exit();
}
?>
