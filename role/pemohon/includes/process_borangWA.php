<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare values for binding
        $user_kp = $_POST['user_kp_raw'] ?? $_POST['user_kp'];
        $jawatan_gred = $_POST['jawatan_gred'];
        $email_penyelia = $_POST['email_penyelia'];
        $alamat_menetap_1 = $_POST['alamat_menetap_1'];
        $alamat_menetap_2 = $_POST['alamat_menetap_2'];
        $poskod_menetap = $_POST['poskod_menetap'];
        $bandar_menetap = $_POST['bandar_menetap'];
        $negeri_menetap = $_POST['negeri_menetap'];
        $alamat_berkhidmat_1 = $_POST['alamat_berkhidmat_1'];
        $alamat_berkhidmat_2 = $_POST['alamat_berkhidmat_2'];
        $poskod_berkhidmat = $_POST['poskod_berkhidmat'];
        $bandar_berkhidmat = $_POST['bandar_berkhidmat'];
        $negeri_berkhidmat = $_POST['negeri_berkhidmat'];
        $tarikh_lapor_diri = $_POST['tarikh_lapor_diri'];
        $tarikh_terakhir_kemudahan = ($_POST['pernah_guna'] === 'ya') ? $_POST['tarikh_terakhir_kemudahan'] : null;
        
        // Partner information
        $nama_first_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['nama_first_pasangan'] : null;
        $nama_last_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['nama_last_pasangan'] : null;
        $no_kp_pasangan = ($_POST['ada_pasangan'] === 'ya') ? ($_POST['no_kp_pasangan_raw'] ?? $_POST['no_kp_pasangan']) : null;
        $alamat_berkhidmat_1_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['alamat_berkhidmat_1_pasangan'] : null;
        $alamat_berkhidmat_2_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['alamat_berkhidmat_2_pasangan'] : null;
        $poskod_berkhidmat_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['poskod_berkhidmat_pasangan'] : null;
        $bandar_berkhidmat_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['bandar_berkhidmat_pasangan'] : null;
        $negeri_berkhidmat_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['negeri_berkhidmat_pasangan'] : null;
        $wilayah_menetap_pasangan = ($_POST['ada_pasangan'] === 'ya') ? $_POST['wilayah_menetap_pasangan'] : null;

        // Check if record exists and its stage
        $check_sql = "SELECT id, wilayah_asal_from_stage FROM wilayah_asal WHERE user_kp = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $user_kp);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $existing_record = $result->fetch_assoc();

        if ($existing_record && ($existing_record['wilayah_asal_from_stage'] === 'BorangWA2' || $existing_record['wilayah_asal_from_stage'] === 'BorangWA5')) {
            // Update existing record
            $update_sql = "UPDATE wilayah_asal SET 
                jawatan_gred = ?,
                email_penyelia = ?,
                alamat_menetap_1 = ?,
                alamat_menetap_2 = ?,
                poskod_menetap = ?,
                bandar_menetap = ?,
                negeri_menetap = ?,
                alamat_berkhidmat_1 = ?,
                alamat_berkhidmat_2 = ?,
                poskod_berkhidmat = ?,
                bandar_berkhidmat = ?,
                negeri_berkhidmat = ?,
                tarikh_lapor_diri = ?,
                tarikh_terakhir_kemudahan = ?,
                nama_first_pasangan = ?,
                nama_last_pasangan = ?,
                no_kp_pasangan = ?,
                alamat_berkhidmat_1_pasangan = ?,
                alamat_berkhidmat_2_pasangan = ?,
                poskod_berkhidmat_pasangan = ?,
                bandar_berkhidmat_pasangan = ?,
                negeri_berkhidmat_pasangan = ?,
                wilayah_menetap_pasangan = ?
                WHERE id = ?";

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssssssssssssssssssssi",
                $jawatan_gred,
                $email_penyelia,
                $alamat_menetap_1,
                $alamat_menetap_2,
                $poskod_menetap,
                $bandar_menetap,
                $negeri_menetap,
                $alamat_berkhidmat_1,
                $alamat_berkhidmat_2,
                $poskod_berkhidmat,
                $bandar_berkhidmat,
                $negeri_berkhidmat,
                $tarikh_lapor_diri,
                $tarikh_terakhir_kemudahan,
                $nama_first_pasangan,
                $nama_last_pasangan,
                $no_kp_pasangan,
                $alamat_berkhidmat_1_pasangan,
                $alamat_berkhidmat_2_pasangan,
                $poskod_berkhidmat_pasangan,
                $bandar_berkhidmat_pasangan,
                $negeri_berkhidmat_pasangan,
                $wilayah_menetap_pasangan,
                $existing_record['id']
            );

            if (!$update_stmt->execute()) {
                throw new Exception("Error updating record: " . $update_stmt->error);
            }

            $insert_id = $existing_record['id'];
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO wilayah_asal (
                user_kp, jawatan_gred, email_penyelia,
                alamat_menetap_1, alamat_menetap_2, poskod_menetap, bandar_menetap, negeri_menetap,
                alamat_berkhidmat_1, alamat_berkhidmat_2, poskod_berkhidmat, bandar_berkhidmat, negeri_berkhidmat,
                tarikh_lapor_diri, tarikh_terakhir_kemudahan,
                nama_first_pasangan, nama_last_pasangan, no_kp_pasangan,
                alamat_berkhidmat_1_pasangan, alamat_berkhidmat_2_pasangan,
                poskod_berkhidmat_pasangan, bandar_berkhidmat_pasangan, negeri_berkhidmat_pasangan,
                wilayah_menetap_pasangan
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssssssssssssssssssssssss",
                $user_kp,
                $jawatan_gred,
                $email_penyelia,
                $alamat_menetap_1,
                $alamat_menetap_2,
                $poskod_menetap,
                $bandar_menetap,
                $negeri_menetap,
                $alamat_berkhidmat_1,
                $alamat_berkhidmat_2,
                $poskod_berkhidmat,
                $bandar_berkhidmat,
                $negeri_berkhidmat,
                $tarikh_lapor_diri,
                $tarikh_terakhir_kemudahan,
                $nama_first_pasangan,
                $nama_last_pasangan,
                $no_kp_pasangan,
                $alamat_berkhidmat_1_pasangan,
                $alamat_berkhidmat_2_pasangan,
                $poskod_berkhidmat_pasangan,
                $bandar_berkhidmat_pasangan,
                $negeri_berkhidmat_pasangan,
                $wilayah_menetap_pasangan
            );

            if (!$insert_stmt->execute()) {
                throw new Exception("Error inserting record: " . $insert_stmt->error);
            }

            $insert_id = $insert_stmt->insert_id;
        }

        // Update wilayah_asal_from_stage
        $update_stage_sql = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA2' WHERE id = ?";
        $update_stage_stmt = $conn->prepare($update_stage_sql);
        $update_stage_stmt->bind_param("i", $insert_id);
        $update_stage_stmt->execute();
        $update_stage_stmt->close();

        // Store form data in session for next step
        $_SESSION['borangWA_data'] = [
            'user_kp' => $user_kp,
            'jawatan_gred' => $jawatan_gred,
            'email_penyelia' => $email_penyelia,
            'alamat_menetap_1' => $alamat_menetap_1,
            'alamat_menetap_2' => $alamat_menetap_2,
            'poskod_menetap' => $poskod_menetap,
            'bandar_menetap' => $bandar_menetap,
            'negeri_menetap' => $negeri_menetap,
            'alamat_berkhidmat_1' => $alamat_berkhidmat_1,
            'alamat_berkhidmat_2' => $alamat_berkhidmat_2,
            'poskod_berkhidmat' => $poskod_berkhidmat,
            'bandar_berkhidmat' => $bandar_berkhidmat,
            'negeri_berkhidmat' => $negeri_berkhidmat,
            'tarikh_lapor_diri' => $tarikh_lapor_diri,
            'pernah_guna' => $_POST['pernah_guna'],
            'tarikh_terakhir_kemudahan' => $tarikh_terakhir_kemudahan,
            
            // Partner Information
            'ada_pasangan' => $_POST['ada_pasangan'],
            'nama_first_pasangan' => $nama_first_pasangan,
            'nama_last_pasangan' => $nama_last_pasangan,
            'no_kp_pasangan' => $no_kp_pasangan,
            'wilayah_menetap_pasangan' => $wilayah_menetap_pasangan,
            'alamat_berkhidmat_1_pasangan' => $alamat_berkhidmat_1_pasangan,
            'alamat_berkhidmat_2_pasangan' => $alamat_berkhidmat_2_pasangan,
            'poskod_berkhidmat_pasangan' => $poskod_berkhidmat_pasangan,
            'bandar_berkhidmat_pasangan' => $bandar_berkhidmat_pasangan,
            'negeri_berkhidmat_pasangan' => $negeri_berkhidmat_pasangan
        ];

        // Store the inserted ID in session
        $_SESSION['wilayah_asal_id'] = $insert_id;

        // Redirect to the next form based on the stage
        if ($existing_record && $existing_record['wilayah_asal_from_stage'] === 'BorangWA5') {
            header("Location: ../borangWA5.php");
        } else {
            header("Location: ../borangWA2.php");
        }
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../borangWA.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../borangWA.php");
    exit();
}
?> 