<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include '../../../connection.php';

// Retrieve latest application
$sql = "SELECT * FROM admin ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // $nama = $data['nama'];
    // $tarikh = $data['tarikh_lapor_diri'];
    // $kategori = $data['kategori'];

    $nama = "Hanis";
    $tarikh = "17/05/2025";
    $kategori = "Pengguna";


    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haniszainee1105@gmail.com';             // your Gmail
        $mail->Password = 'eizx afua iazr efrl';                // app password (not Gmail login!)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('haniszainee1105@gmail.com', 'Sistem Permohonan');
        $mail->addAddress('2023168781@student.uitm.edu.my', 'Pegawai HR');

        $mail->isHTML(true);
        $mail->Subject = 'Permohonan Baru Diterima';
        $mail->Body = "
            <h3>Notifikasi Permohonan</h3>
            <p><strong>Nama:</strong> $nama</p>
            <p><strong>Tarikh Lapor Diri:</strong> $tarikh</p>
            <p><strong>Kategori:</strong> $kategori</p>
            <p>Sila semak sistem untuk maklumat lanjut.</p>
        ";

        $mail->send();
        echo "✅ Emel berjaya dihantar.";
    } catch (Exception $e) {
        echo "❌ Emel gagal dihantar. Ralat: {$mail->ErrorInfo}";
    }
} else {
    echo "❌ Tiada permohonan ditemui dalam pangkalan data.";
}

?>
