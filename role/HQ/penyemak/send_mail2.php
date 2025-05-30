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
        $status_permohonan = $_POST['status_permohonan'];
        $admin_id = $_SESSION['admin_id'];
        $status = 'Menunggu pengesahan PBR2 CSM';


          // Handle File Uploads
          if (!empty($_FILES['dokumen']['name'][0])) {
            foreach ($_FILES['dokumen']['name'] as $key => $name) {
                $dokumen_name = $_FILES['dokumen']['name'][$key]; // Original file name
                $dokumen_tmp = $_FILES['dokumen']['tmp_name'][$key];

                $upload_path = '../../../documents/' . basename($dokumen_name);

                if (move_uploaded_file($dokumen_tmp, $upload_path)) {
                    // Insert both file_name and file_path
                    $stmt_doc = $conn->prepare("INSERT INTO documents (file_name, file_path, wilayah_asal_id) VALUES (?, ?, ?)");
                    $stmt_doc->bind_param("ssi", $dokumen_name, $upload_path, $wilayah_asal_id);
                    $stmt_doc->execute();
                    $stmt_doc->close();
                } 
            }
        }

        

        $tarikh_keputusan = date('Y-m-d H:i:s');
        // 2. Update wilayah_asal
        $stmt_wilayah = $conn->prepare("UPDATE wilayah_asal SET status = ?, penyemak_HQ2_id = ?, tarikh_keputusan_penyemak_HQ2 = ? WHERE id = ?");
        $stmt_wilayah->bind_param("sssi", $status, $admin_id, $tarikh_keputusan, $wilayah_asal_id);
        $stmt_wilayah->execute();
        $stmt_wilayah->close();


        $sql = "SELECT * FROM admin WHERE role = 'PBR CSM'";
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
                    $mail->Username = 'haniszainee1105@gmail.com';  // your Gmail
                    $mail->Password = 'eizx afua iazr efrl';         // Gmail App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
        
                    $mail->setFrom($mail->Username, 'ALLTRAS System');
                    $mail->addAddress($receiver_email, $receiver_name);
        
                    $mail->isHTML(true);
                    $mail->Subject = 'Permohonan Tambang Ziarah Wilayah (TZW) : Permohonan Diluluskan';
                    $mail->Body = "
                        <br><p>Assalamualaikum dan Salam sejahtera,</p>
                        <p>Tuan/Puan,</p><br>
                        <p><b>Permohonan Kemudahan Tambang Ziarah Wilayah</b></p><br>
        
                        <p><b>Nama Pegawai :</b> $nama</p>
                        <p><b>No.Kad Pengenalan :</b> $kp</p>
                        <p><b>Bahagian/Cawangan :</b> $bahagian</p><br>
        
                        <p>Permohonan Tambang Ziarah Wilayah (TZW) pegawai telah <b>DILULUSKAN</b> oleh Ketua Jabatan. Kelulusan ini hendaklah direkodkan ke dalam Buku Perkhidmatan Pegawai. </p>
                        <p>Sila klik pautan/butang di bawah untuk tindakan lanjut dan maklumat permohonan.</p>
        
                        <p><a href='http://localhost/reAllTras/role/csm/pbr/viewdetailsfromHQ.php?kp=$kp'><b><u>PAPAR MAKLUMAT PERMOHONAN</u></b></a></p><br>
        
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
        