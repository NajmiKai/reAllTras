<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include_once '../../../includes/config.php';
include '../../../includes/system_logger.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $wilayah_asal_id = $_POST['wilayah_asal_id'];
        $admin_id = $_SESSION['admin_id'];
        $admin_name = $_SESSION['admin_name'];
        $admin_role = $_SESSION['admin_role'];
        $status = 'Permohonan diluluskan';

        // Get current status before update
        $stmt_current = $conn->prepare("SELECT status FROM wilayah_asal WHERE id = ?");
        $stmt_current->bind_param("i", $wilayah_asal_id);
        $stmt_current->execute();
        $result_current = $stmt_current->get_result();
        $current_data = $result_current->fetch_assoc();
        $old_status = $current_data['status'];


           // 1. Handle Multiple File Uploads
           if (!empty($_FILES['dokumen']['name'][0])) {
            foreach ($_FILES['dokumen']['name'] as $key => $name) {

                $file_name = $_FILES['dokumen']['name'][$key];               // file_name
                $file_type = $_FILES['dokumen']['type'][$key];               // file_type
                $file_size = $_FILES['dokumen']['size'][$key];               // file_size
                $description = "E-tiket";   
            // Build unique filename
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        
            $unique_filename = uniqid() . '_' . $wilayah_asal_id . '_' . str_replace(' ', '_', $description) . '.' . $file_extension;
            
            $dokumen_tmp = $_FILES['dokumen']['tmp_name'][$key];
            $upload_dir = '../../../uploads/kewangan';
            $target_path = $upload_dir . '/' . $unique_filename;

             // Create folder if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
        
            if (move_uploaded_file($dokumen_tmp, $target_path)) {
                // File path to be stored in DB (web-accessible)
                $web_path = '../../../uploads/kewangan/' . $unique_filename;

                $sql = "INSERT INTO documents (
                            wilayah_asal_id,       -- INT
                            file_name,             -- VARCHAR
                            file_path,             -- VARCHAR
                            file_type,             -- VARCHAR
                            file_size,             -- INT
                            description,           -- TEXT
                            file_origin_id,       -- VARCHAR
                            file_origin              -- ENUM
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'kewangan')";
        
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssiss",
                    $wilayah_asal_id,
                    $file_name,
                    $web_path,
                    $file_type,
                    $file_size,
                    $description,
                    $admin_id 
                );
        
                if ($stmt->execute()) {
                    // return true;
                } else {
                    error_log("DB error: " . $stmt->error);
                }
            } else {
                error_log("Failed to move uploaded file.");
            }
    }
}

    
        $tarikh_keputusan = date('Y-m-d H:i:s');
        $status_permohonan = "Selesai";
        $kedudukan_permohonan = "Kewangan";

        $stmt_wilayah = $conn->prepare("UPDATE wilayah_asal SET status = ?,  status_permohonan = ?, kedudukan_permohonan = ?, penyediaKemudahan_kewangan_id = ?, tarikh_keputusan_penyediaKemudahan_kewangan = ? WHERE id = ?");
        $stmt_wilayah->bind_param("sssssi", $status, $status_permohonan, $kedudukan_permohonan, $admin_id, $tarikh_keputusan, $wilayah_asal_id);
        $stmt_wilayah->execute();
        $stmt_wilayah->close();

        // Log the status change
        logApplicationStatusChange($conn, 'admin', $admin_id, $wilayah_asal_id, $old_status, $status, "Penyedia Kemudahan Kewangan updated application status");


          
          //insert into document_logs
          $tindakan = "Telah muat naik e-tiket";
          $ulasan = "-";
  
          $log_sql = "INSERT INTO document_logs (tarikh, namaAdmin, peranan, tindakan, catatan, wilayah_asal_id) VALUES (NOW(), ?, ?, ?, ?, ?)";
                
          $log_stmt = $conn->prepare($log_sql);
          $log_stmt->bind_param("ssssi", $admin_name, $admin_role, $tindakan, $ulasan, $wilayah_asal_id);
                
          if (!$log_stmt->execute()) {
            error_log("Gagal masukkan ke document_logs: " . $log_stmt->error);
          }
          $log_stmt->close();


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
                    $mail->Username = 'alltras@customs.gov.my';  // your Gmail
                    $mail->Password = 'wyob jyxf gzsy gbax';         // Gmail App Password 
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
        
                    $mail->setFrom($mail->Username, 'ALLTRAS');
                    $mail->addAddress($receiver_email);
        
                    $mail->isHTML(true);

                    $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Booking Number';
                    $mail->Body = "
                        <br><p>Assalamualaikum dan Salam sejahtera,</p>
                        <p>Tuan/Puan,</p><br>
                        <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>
        
                        <p><b>Nama Pegawai :</b> $nama</p>
                        <p><b>No.Kad Pengenalan :</b> $kp</p>
                        <p><b>Bahagian/Cawangan :</b> $bahagian</p><br>
        
                        <p>Dimaklumkan bahawa permohonan Kemudahan Tambang Ziarah Wilayah(TZW) tuan/puan telah <b>DILULUSKAN</b> dan <b>SELESAI DIPROSES</b>. <i>Booking Number</i> telah dimuat naik ke dalam ALLTRAS untuk tindakan dan rujukan tuan/puan selanjutnya.</p>
                        <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>
        
                        <p><a href='" . getFullUrl("role/pemohon/dashboard.php") . "'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>
        
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
        
        header("Location: wilayahAsal.php");
        exit();
    }
        