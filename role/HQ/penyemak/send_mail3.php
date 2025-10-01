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
        $status = 'Kembali ke PBR CSM';
        $ulasan = $_POST['ulasan'] ?? null; // Get ulasan if provided, else set to null

        // Get current status before update
        $stmt_current = $conn->prepare("SELECT status FROM wilayah_asal WHERE id = ?");
        $stmt_current->bind_param("i", $wilayah_asal_id);
        $stmt_current->execute();
        $result_current = $stmt_current->get_result();
        $current_data = $result_current->fetch_assoc();
        $old_status = $current_data['status'];

        $tarikh_keputusan = date('Y-m-d H:i:s');
        $status_permohonan = "Dikuiri";
        $kedudukan_permohonan = "HQ";

        $stmt_wilayah = $conn->prepare("UPDATE wilayah_asal SET status = ?, ulasan_penyemak_HQ = ?, penyemak_HQ1_id = ?,  status_permohonan= ?, kedudukan_permohonan= ?, tarikh_keputusan_penyemak_HQ1 = ? WHERE id = ?");
        $stmt_wilayah->bind_param("ssssssi", $status, $ulasan, $admin_id, $status_permohonan, $kedudukan_permohonan, $tarikh_keputusan, $wilayah_asal_id);
        $stmt_wilayah->execute();
        $stmt_wilayah->close();

         // Log the status change
         logApplicationStatusChange($conn, 'admin', $admin_id, $wilayah_asal_id, $old_status, $status, "Penyemak HQ updated application status");


          //insert into document_logs
          $tindakan = "Dikuiri";
  
          $log_sql = "INSERT INTO document_logs (tarikh, namaAdmin, peranan, tindakan, catatan, wilayah_asal_id) VALUES (NOW(), ?, ?, ?, ?, ?)";
                
          $log_stmt = $conn->prepare($log_sql);
          $log_stmt->bind_param("ssssi", $admin_name, $admin_role, $tindakan, $ulasan, $wilayah_asal_id);
                
          if (!$log_stmt->execute()) {
            error_log("Gagal masukkan ke document_logs: " . $log_stmt->error);
          }
          $log_stmt->close();

        
          $sql = "SELECT * FROM admin WHERE role = 'PBR CSM'";
          $result = $conn->query($sql);
          
          if ($result->num_rows > 0) {
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
                    $mail->Password = 'wyob jyxf gzsy gbax';     // Gmail App Password 
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
        
                    $mail->setFrom($mail->Username, 'ALLTRAS');
                    $mail->addAddress($receiver_email);
        
                    $mail->isHTML(true);
                    $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Kuiri Permohonan ';
                    $mail->Body = "
                        <br><p>Assalamualaikum dan Salam sejahtera,</p>
                        <p>Tuan/Puan,</p><br>
                        <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>
        
                        <p><b>Nama Pegawai :</b> $nama</p>
                        <p><b>No.Kad Pengenalan :</b> $kp</p>
                        <p><b>Bahagian/Cawangan :</b> $bahagian</p>
                        <p><b>Jabatan :</b> JKDM WILAYAH PERSEKUTUAN KUALA LUMPUR</p><br>
        
                        <p>Permohonan Tambang Ziarah Wilayah (TZW) oleh pegawai telah <b>DIKUIRI</b>. <br>

                        <p>Mohon tuan/puan mengambil tindakan dan menghantar permohonan ke pihak yang berkaitan.</p>
                        <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>    
        
                        <p><a href='" . getFullUrl("role/csm/pbr/viewdetailskuiri.php?kp=$kp") . "'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>

        
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
        