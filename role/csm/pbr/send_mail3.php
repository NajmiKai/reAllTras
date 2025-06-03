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

        $wilayah_asal_id = $_POST['wilayah_asal_id'];
        // $keputusan = $_POST['keputusan'];
        $admin_id = $_SESSION['admin_id'];
        $status = 'Permohonan dikuiri';

        $tarikh_keputusan = date('Y-m-d H:i:s');
        $status_permohonan = "Dikuiri";
        $kedudukan_permohonan = "Pemohon";

        $stmt_wilayah = $conn->prepare("UPDATE wilayah_asal SET status = ?,  status_permohonan= ?, kedudukan_permohonan= ? WHERE id = ?");
        $stmt_wilayah->bind_param("sssi", $status, $status_permohonan, $kedudukan_permohonan, $wilayah_asal_id);
        $stmt_wilayah->execute();
        $stmt_wilayah->close();


          // Fetch user details
        $stmt_user = $conn->prepare("
            SELECT u.nama_first, u.nama_last, u.kp, u.bahagian, u.email
            FROM wilayah_asal wa
            JOIN user u ON wa.user_kp = u.kp
            WHERE wa.id = ?
        ");
        $stmt_user->bind_param("i", $wilayah_asal_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            $userData = $result_user->fetch_assoc();
            $nama = $userData['nama_first'] . ' ' . $userData['nama_last'];
            $kp = $userData['kp'];
            $bahagian = $userData['bahagian'];
            $receiver_email = $userData['email'];

            if (!empty($receiver_email)) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'haniszainee1105@gmail.com';  // your Gmail
                    $mail->Password = 'eizx afua iazr efrl';         // Gmail App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
        
                    $mail->setFrom($mail->Username);
                    $mail->addAddress($receiver_email);
        
                    $mail->isHTML(true);
                    $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Kuiri Permohonan ';
                    $mail->Body = "
                        <br><p>Assalamualaikum dan Salam sejahtera,</p>
                        <p>Tuan/Puan,</p><br>
                        <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>
        
                        <p><b>Nama Pegawai :</b> $nama</p>
                        <p><b>No.Kad Pengenalan :</b> $kp</p>
                        <p><b>Bahagian/Cawangan :</b> $bahagian</p><br>
        
                        <p>Permohonan Tambang Ziarah Wilayah (TZW) oleh pegawai telah <b>DIKUIRI</b>. <br>
                        <p>Mohon tuan/puan mengambil tindakan pembetulan dan menghantar semula permohonan untuk semakan lanjut.</p>
                        <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>
        
                        <p><a href='http://localhost/reAllTras/role/pemohon/dashboard.php'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>
        
                        <p>Sekian, terima kasih.</p>
                        <p>Emel ini dijana secara automatik oleh <i>All Region Travelling System (ALLTRAS)</i></p>
                        <p>Jabatan Kastam Diraja Malaysia</p>
                    ";
        
                    $mail->send();
                    $_SESSION['status'] = 'success';
                } catch (Exception $e) {
                    $_SESSION['status'] = 'fail';
                    $_SESSION['error'] = $mail->ErrorInfo;
                }
            }
        } 
        
        header("Location: permohonanDikuiri.php");
        exit();
    }
        