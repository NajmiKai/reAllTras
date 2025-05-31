<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
include 'connection.php';
date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $nama = $admin['Name'];

        // Generate reset token & expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600);

        // Save token in DB
        $stmt = $conn->prepare("UPDATE admin SET reset_token = ?, token_expiry = ? WHERE Email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $resetLink = "http://localhost/reAllTras/resetPassword.php?token=$token";

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haniszainee1105@gmail.com';
            $mail->Password = 'eizx afua iazr efrl';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('haniszainee1105@gmail.com', 'ALLTRAS');
            $mail->addAddress($email, $nama);

            $mail->isHTML(true);
            $mail->Subject = 'Pautan Reset Kata Laluan';
            $mail->Body = "
                <h3>Permintaan Reset Kata Laluan</h3>
                <p>Salam dan selamat sejahtera $nama,</p>
                <p>Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>Jika anda tidak membuat permintaan reset kata laluan, sila abaikan emel ini.</p>
            ";

            $mail->send();
            header("Location: forgotpassword.php?status=sent");
           // echo "<script>alert('Reset link sent to your email.'); window.location.href = 'forgotpassword.php';</script>";
            exit;
            
          

        } catch (Exception $e) {
            echo "<script>alert('Email gagal dihantar: {$mail->ErrorInfo}'); window.location.href = 'forgotpassword.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak dijumpai.'); window.location.href = 'forgotpassword.php';</script>";
    }
}
?>
