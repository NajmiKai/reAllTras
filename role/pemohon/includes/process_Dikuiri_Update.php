<?php
include '../../connection.php';
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../loginUser.php");
    exit();
}

// Check if wilayah_asal_id exists
if (!isset($_SESSION['wilayah_asal_id'])) {
    $_SESSION['error'] = "ID Wilayah Asal tidak dijumpai.";
    header("Location: ../dikuiriWA.php");
    exit();
}

$wilayah_asal_id = $_SESSION['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Update confirmation status and date
    $sql = "UPDATE wilayah_asal SET 
            pengesahan_user = true, 
            tarikh_pengesahan_user = NOW(),
            wilayah_asal_form_fill = true,
            wilayah_asal_from_stage = 'Hantar',
            status_permohonan = 'Belum Disemak',
            kedudukan_permohonan = 'Pemohon',
            status = 'Menunggu pengesahan PBR CSM',
            ulasan_pbr_csm1 = NULL,
            pbr_csm1_id = NULL,
            ulasan_pengesah_csm1 = NULL,
            pengesah_csm1_id = NULL,
            tarikh_keputusan_csm1 = NULL
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wilayah_asal_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal mengemaskini status pengesahan.");
    }

    // Commit transaction
    $conn->commit();

    // Get user details for email
    $sql = "SELECT u.nama_first, u.nama_last, u.kp, u.bahagian, u.email, wa.jawatan_gred,
            wa.tarikh_penerbangan_pergi, wa.tarikh_penerbangan_balik, wa.start_point, wa.end_point
            FROM user u 
            JOIN wilayah_asal wa ON u.kp = wa.user_kp 
            WHERE wa.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    // Get all PBR CSM admin emails
    $sql_csm = "SELECT Email, Name FROM admin WHERE Role = 'PBR CSM'";
    $result_csm = $conn->query($sql_csm);
    $csm_emails = [];
    if ($result_csm && $result_csm->num_rows > 0) {
        while ($csm_data = $result_csm->fetch_assoc()) {
            $csm_emails[] = [
                'email' => $csm_data['Email'],
                'name' => $csm_data['Name']
            ];
        }
    }

    // Format dates
    $tarikh_pergi = date('d/m/Y', strtotime($user_data['tarikh_penerbangan_pergi']));
    $tarikh_balik = date('d/m/Y', strtotime($user_data['tarikh_penerbangan_balik']));

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alltras@customs.gov.my';  // your Gmail
        $mail->Password = 'wyob jyxf gzsy gbax';         // Gmail App Password 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mail->Username);
        $mail->addAddress($user_data['email']);
        
        // Add all PBR CSM admins as CC recipients
        foreach ($csm_emails as $csm) {
            $mail->addCC($csm['email'], $csm['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Tindakan Semakan Permohonan';
        $mail->Body = "
            <br><p>Assalamualaikum dan Salam sejahtera,</p>
            <p>Tuan/Puan,</p><br>
            <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>

            <p><b>Nama Pegawai :</b> {$user_data['nama_first']} {$user_data['nama_last']}</p>
            <p><b>No.Kad Pengenalan :</b> {$user_data['kp']}</p>
            <p><b>Bahagian/Cawangan :</b> {$user_data['bahagian']}</p><br>
            <p><b>Destinasi Ziarah :</b> {$user_data['end_point']}</p><br>
            <p><b>Maklumat Perjalanan :</b><br>
            Tarikh Penerbangan Pergi: {$tarikh_pergi}<br>
            Tarikh Penerbangan Balik: {$tarikh_balik}<br>
            Lokasi Berlepas: {$user_data['start_point']}<br>
            Lokasi Tiba: {$user_data['end_point']}</p><br>

            <p>Mohon pihak tuan/puan untuk menyemak maklumat pemohon dan mengambil tindakan sewajarnya.</p>
            <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>

            <p><a href='http://localhost/reAllTras/role/csm/pegawaiSulit/viewdetails.php?kp={$user_data['kp']}'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>

            <p>Sekian, terima kasih.</p>
            <p>Emel ini dijana secara automatik oleh <i>All Region Travelling System (ALLTRAS)</i></p>
            <p>Jabatan Kastam Diraja Malaysia</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log email error but don't stop the process
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }

    // Clear session data
    unset($_SESSION['wilayah_asal_id']);
    unset($_SESSION['borangWA_data']);
    unset($_SESSION['parent_info']);

    $_SESSION['success'] = "Permohonan anda telah berjaya dihantar. Sila tunggu untuk kelulusan.";
    header("Location: dashboard.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error'] = $e->getMessage();
    header("Location: wilayahAsal.php");
    exit();
}
?> 