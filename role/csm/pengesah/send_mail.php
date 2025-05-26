<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include '../../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve latest admin with role 'Pengesah CSM'
    $sql = "SELECT * FROM admin WHERE role = 'Pelulus HQ'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $receiver_name = $data['nama'];   // make sure your DB column is 'nama'
        $receiver_email = $data['email']; // and 'email'

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haniszainee1105@gmail.com';  // Gmail used to send
            $mail->Password = 'eizx afua iazr efrl';         // Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($mail->Username, 'ALLTRAS System');
            $mail->addAddress($receiver_email, $receiver_name);

            $mail->isHTML(true);
            $mail->Subject = 'ALLTRAS: Memerlukan Pengesahan Peruntukan Kewangan';
            $mail->Body = "
                <p>Salam sejahtera <strong>$receiver_name</strong>,</p><br>
                <p>Dimaklumkan bahawa terdapat permohonan penyelenggaraan kenderaan yang memerlukan pengesahan peruntukan kewangan melalui Sistem ALLTRAS.</p>
                <p>Sila semak sistem untuk maklumat lanjut.</p>
                <p><u>PAPAR MAKLUMAT PERMOHONAN</u></p><br><br>

                <p>Sekian, terima kasih.</p>
                <p>Pasukan ALLTRAS</p>
                <p>Jabatan Kastam Diraja Malaysia</p>
            ";

            $mail->send();
            $_SESSION['status'] = 'success';
        } catch (Exception $e) {
            $_SESSION['status'] = 'fail';
            $_SESSION['error'] = $mail->ErrorInfo;
        }
    } else {
        $_SESSION['status'] = 'no_admin';
    }

    // Redirect back to previous page
    $backUrl = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header("Location: $backUrl");
    exit();

} else {
    $_SESSION['status'] = 'no_post';
    $backUrl = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header("Location: $backUrl");
    exit();
}
