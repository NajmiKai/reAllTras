<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

session_start();
include_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate and extract input
    $wilayah_asal_id = $_POST['wilayah_asal_id'];
    $ulasan = $_POST['ulasan'] ?? "-";

    $super_admin_id = $_SESSION['super_admin_id'];
    $super_admin_name = $_SESSION['super_admin_name'];
    $admin_role = "Super Admin";

    $status = 'Permohonan dibatalkan';
    $status_permohonan = "Batal";
    $tarikh_keputusan = date('Y-m-d H:i:s');
    $kedudukan_permohonan = "Pemohon";

    // 1. Update wilayah_asal status
    $stmt = $conn->prepare("UPDATE wilayah_asal SET status = ?, status_permohonan = ?, ulasan_superadmin = ?, tarikh_keputusan_superadmin = ?, kedudukan_permohonan = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $status, $status_permohonan, $ulasan, $tarikh_keputusan, $kedudukan_permohonan, $wilayah_asal_id);
    $stmt->execute();
    $stmt->close();

    // 2. Insert into document_logs
    $tindakan = "Dibatalkan";
    $log_stmt = $conn->prepare("INSERT INTO document_logs (tarikh, namaAdmin, peranan, tindakan, catatan, wilayah_asal_id) VALUES (NOW(), ?, ?, ?, ?, ?)");
    $log_stmt->bind_param("ssssi", $super_admin_name, $admin_role, $tindakan, $ulasan, $wilayah_asal_id);
    $log_stmt->execute();
    $log_stmt->close();

    // 3. Get user info
    $stmt_user = $conn->prepare("
        SELECT u.nama_first, u.nama_last, u.kp, u.bahagian, u.email, wa.email_penyelia 
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
        $user_email = $userData['email'];
        $penyelia_email = $userData['email_penyelia'];
    } else {
        $nama = $kp = $bahagian = $user_email = $penyelia_email = "Tidak Dikenal Pasti";
    }
    $stmt_user->close();

    // 4. Get CC list
    $cc_emails = [];
    $admin_sql = "SELECT email FROM admin WHERE role IN ('PBR CSM', 'Penyedia Kemudahan Kewangan', 'Penyemak HQ')";
    $result = $conn->query($admin_sql);
    while ($row = $result->fetch_assoc()) {
        $cc_emails[] = $row['email'];
    }

    // 5. Send Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alltras@customs.gov.my';
        $mail->Password = 'wyob jyxf gzsy gbax'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mail->Username, 'ALLTRAS');
        $mail->addAddress($user_email);
        if (!empty($penyelia_email)) 
            $mail->addCC($penyelia_email);//cc penyelia

        foreach ($cc_emails as $cc) { //cc admins
            $mail->addCC($cc);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW): Pembatalan Permohonan';
        $mail->Body = "
            <p>Assalamualaikum dan Salam sejahtera,</p>
            <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p>
            <p><b>Nama Pegawai:</b> $nama<br>
               <b>No. Kad Pengenalan:</b> $kp<br>
               <b>Bahagian/Cawangan:</b> $bahagian</p>
               <p><b>Jabatan :</b> JKDM WILAYAH PERSEKUTUAN KUALA LUMPUR</p><br>
            <p>Permohonan ini telah <strong>DIBATALKAN</strong> atas sebab-sebab tertentu.</p>
            <p>Maklumat ini dimaklumkan untuk tindakan dan rujukan pihak tuan/puan.</p>
            <br><p>Sekian, terima kasih.</p>
            <p><i>Emel ini dijana secara automatik oleh All Region Travelling System (ALLTRAS)<br>
            Jabatan Kastam Diraja Malaysia</i></p>
        ";

        $mail->send();
        $_SESSION['status'] = 'success';
    } catch (Exception $e) {
        $_SESSION['status'] = 'fail';
        $_SESSION['error'] = $mail->ErrorInfo;
    }

    // Redirect
    header("Location: listWilayahAsal.php?delete=success");
    exit();
}

?>
