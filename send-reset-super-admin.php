<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
include_once 'includes/config.php';
date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier'];

    // Check if user exists by email only
    $stmt = $conn->prepare("SELECT * FROM superAdmin WHERE Email = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $nama = $admin['Name'];
        $email = $admin['Email'];

        // Generate reset token & expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600);

        // Save token in DB
        $stmt = $conn->prepare("UPDATE superAdmin SET reset_token = ?, token_expiry = ? WHERE Email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $resetLink = getFullUrl("resetPasswordSuperAdmin.php?token=$token");

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'alltras@customs.gov.my';  // your Gmail
            $mail->Password = 'wyob jyxf gzsy gbax';     // Gmail App Password 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($mail->Username, 'ALLTRAS');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Pautan Reset Kata Laluan Super Admin';
            $mail->Body = "
                <h3>Permintaan Reset Kata Laluan Super Admin</h3>
                <p>Salam dan selamat sejahtera $nama,</p>
                <p>Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>Jika anda tidak membuat permintaan reset kata laluan, sila abaikan emel ini.</p>
            ";

            $mail->send();
            header("Location: forgotpasswordSuperAdmin.php?status=sent");
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Email gagal dihantar: {$mail->ErrorInfo}'); window.location.href = 'forgotpasswordSuperAdmin.php';</script>";
        }
    } else {
        echo "<script>alert('Emel atau KP tidak dijumpai.'); window.location.href = 'forgotpasswordSuperAdmin.php';</script>";
    }
}
?> 