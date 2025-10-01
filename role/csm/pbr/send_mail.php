<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting to see the problem
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include_once '../../../includes/config.php';
include '../../../includes/system_logger.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $wilayah_asal_id = $_POST['wilayah_asal_id'];
        $keputusan = $_POST['keputusan'];
        $admin_id = $_SESSION['admin_id'];
        $admin_name  = $_SESSION['admin_name'];
        $admin_role  = $_SESSION['admin_role'];
        $status = 'Menunggu pengesahan pegawai sulit CSM';

        // Get current status before update
        $stmt_current = $conn->prepare("SELECT status FROM wilayah_asal WHERE id = ?");
        $stmt_current->bind_param("i", $wilayah_asal_id);
        $stmt_current->execute();
        $result_current = $stmt_current->get_result();
        $current_data = $result_current->fetch_assoc();
        $old_status = $current_data['status'];

        $ulasan = null;
        if ($keputusan === 'Tidak diterima') {
            $ulasan = $_POST['ulasan'] ?? null;
        }


    
        // 1. Handle Multiple File Uploads
        if (!empty($_FILES['dokumen']['name'][0])) {
            foreach ($_FILES['dokumen']['name'] as $key => $name) {

                $file_name = $_FILES['dokumen']['name'][$key];               // file_name
                $file_type = $_FILES['dokumen']['type'][$key];               // file_type
                $file_size = $_FILES['dokumen']['size'][$key];               // file_size
                $description = "Buku perkhidmatan";   
            // Build unique filename
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        
            $unique_filename = uniqid() . '_' . $wilayah_asal_id . '_' . str_replace(' ', '_', $description) . '.' . $file_extension;
            
            $dokumen_tmp = $_FILES['dokumen']['tmp_name'][$key];
            $upload_dir = '../../../uploads/csm1';
            $target_path = $upload_dir . '/' . $unique_filename;

             // Create folder if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
        
            if (move_uploaded_file($dokumen_tmp, $target_path)) {
                // File path to be stored in DB (web-accessible)
                $web_path = '../../../uploads/csm1/' . $unique_filename;

                $sql = "INSERT INTO documents (
                            wilayah_asal_id,       -- INT
                            file_name,             -- VARCHAR
                            file_path,             -- VARCHAR
                            file_type,             -- VARCHAR
                            file_size,             -- INT
                            description,           -- TEXT
                            file_origin_id,  -- VARCHAR
                            file_origin      -- ENUM
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'csm1')";
        
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
        // 2. Update wilayah_asal
        $stmt_wilayah = $conn->prepare("UPDATE wilayah_asal SET status = ?, ulasan_pbr_csm1 = ?, pbr_csm1_id = ?, tarikh_keputusan_csm1 = ? WHERE id = ?");
        $stmt_wilayah->bind_param("ssssi", $status, $ulasan, $admin_id, $tarikh_keputusan, $wilayah_asal_id);
        $stmt_wilayah->execute();
        $stmt_wilayah->close();

        // Log the status change
        logApplicationStatusChange($conn, 'admin', $admin_id, $wilayah_asal_id, $old_status, $status, "PBR CSM updated application status");
        
    
    
        //insert into document_logs
        $tindakan = ($keputusan === 'Tidak diterima') ? "Dikuiri" : "Diterima";
        $ulasan = $_POST['ulasan'] ?? "-";
        
        $log_sql = "INSERT INTO document_logs (tarikh, namaAdmin, peranan, tindakan, catatan, wilayah_asal_id) VALUES (NOW(), ?, ?, ?, ?, ?)";
        
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ssssi", $admin_name, $admin_role, $tindakan, $ulasan, $wilayah_asal_id);
        
        if (!$log_stmt->execute()) {
            error_log("Gagal masukkan ke document_logs: " . $log_stmt->error);
        }
        $log_stmt->close();
        


        $sql = "SELECT * FROM admin WHERE role = 'Pegawai Sulit CSM'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {

            // Fetch user details from wilayah_asal and user tables
            $stmt_user = $conn->prepare("
            SELECT u.nama_first, u.nama_last, u.kp, u.bahagian
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
            } else {
            $nama = $kp = $bahagian = "Tidak Dikenal Pasti";
            }
            $stmt_user->close();

            while ($data = $result->fetch_assoc()) {
                $receiver_name = $data['Name'];
                $receiver_email = $data['Email'];
        
                // Send email to each admin
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
                    $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Tindakan Semakan Permohonan';
                    $mail->Body = "
                        <br><p>Assalamualaikum dan Salam sejahtera,</p>
                        <p>Tuan/Puan,</p><br>
                        <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>
        
                        <p><b>Nama Pegawai :</b> $nama</p>
                        <p><b>No.Kad Pengenalan :</b> $kp</p>
                        <p><b>Bahagian/Cawangan :</b> $bahagian</p>
                        <p><b>Jabatan :</b> JKDM WILAYAH PERSEKUTUAN KUALA LUMPUR</p><br>
        
                        <p>Permohonan Tambang Ziarah Wilayah (TZW) oleh pegawai telah disemak dan dikemukakan untuk tindakan selanjutnya oleh Unit Sulit Cawangan Sumber Manusia.</p>
        
                        <p>Mohon pihak tuan/puan untuk semakan dan pengesahan maklumat berkaitan.</p>
                        <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>
        
                        <p><a href='" . getFullUrl("role/csm/pegawaiSulit/viewdetails.php?kp=$kp") . "'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>

        
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
        
        header("Location: permohonanPengguna.php");
        exit();
    }
        