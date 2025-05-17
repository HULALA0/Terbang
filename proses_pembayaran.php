<?php
session_start();

if (isset($_POST['status'])) {
    $_SESSION['status_pembayaran'] = $_POST['status'];

    if ($_POST['status'] === 'SUDAH BAYAR' && isset($_FILES['bukti'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmp = $_FILES['bukti']['tmp_name'];
        $fileName = basename($_FILES['bukti']['name']);
        $destPath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($fileTmp, $destPath)) {
            $_SESSION['bukti_pembayaran'] = $destPath;
        }
    }
}

header("Location: index.php");
exit;
?>
