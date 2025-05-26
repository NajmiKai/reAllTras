<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include '../../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// Retrieve latest application
$sql = "SELECT * FROM admin where role  = 'Pengesah CSM'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    $receiver_name = $data['Name'];
    $receiver_email = $data['Email'];

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haniszainee1105@gmail.com';  // Gmail used to send
        $mail->Password = 'eizx afua iazr efrl';         // Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Dynamic sender
        $mail->setFrom($mail->Username, 'ALLTRAS System'); // or use $admin_email if verified
        // Dynamic receiver
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
// Redirect back to the form page
header("Location: wilayahAsal.php"); // <-- replace with your real page
exit();
} else {
$_SESSION['status'] = 'no_post';
header("Location: wilayahAsal.php");
exit();
}
?>