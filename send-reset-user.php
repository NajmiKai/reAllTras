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
    $identifier = $_POST['identifier'];
    
    // Only allow email
    if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Sila masukkan emel yang sah.'); window.location.href = 'forgotpasswordUser.php';</script>";
        exit;
    }
    
    // Search by email only
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $email = $user['email']; // Get email for sending reset link
        $nama = $user['nama_first'] . ' ' . $user['nama_last'];

        // Generate reset token & expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600);

        // Save token in DB
        $stmt = $conn->prepare("UPDATE user SET reset_token = ?, token_expiry = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expiry, $user['id']);
        $stmt->execute();

        $resetLink = getFullUrl("resetPasswordUser.php?token=$token");

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

            $mail->setFrom($mail->Username);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Kata Laluan ALLTRAS';
            $mail->Body = "
                <h3>Permintaan Reset Kata Laluan</h3>
                <p>Hai $nama,</p>
                <p>Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>Pautan ini akan tamat tempoh dalam masa 1 jam.</p>
                <p>Jika anda tidak meminta reset kata laluan, sila abaikan emel ini.</p>
            ";

            $mail->send();
            header("Location: forgotpasswordUser.php?status=sent");
            exit;

        } catch (Exception $e) {
            echo "<script>alert('Gagal menghantar emel: {$mail->ErrorInfo}'); window.location.href = 'forgotpasswordUser.php';</script>";
        }
    } else {
        echo "<script>alert('Emel tidak dijumpai.'); window.location.href = 'forgotpasswordUser.php';</script>";
    }
}
?>
