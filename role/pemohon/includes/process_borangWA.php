<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get wilayah_asal_id from POST or session
        $wilayah_asal_id = $_POST['wilayah_asal_id'] ?? $_SESSION['wilayah_asal_id'] ?? null;

        // If wilayah_asal_id exists, update existing record
        if ($wilayah_asal_id) {
            $sql = "UPDATE wilayah_asal SET 
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
                pernah_guna = ?,
                tarikh_terakhir_kemudahan = ?,
                ada_pasangan = ?,
                nama_first_pasangan = ?,
                nama_last_pasangan = ?,
                no_kp_pasangan = ?,
                wilayah_menetap_pasangan = ?,
                alamat_berkhidmat_1_pasangan = ?,
                alamat_berkhidmat_2_pasangan = ?,
                poskod_berkhidmat_pasangan = ?,
                bandar_berkhidmat_pasangan = ?,
                negeri_berkhidmat_pasangan = ?,
                wilayah_asal_from_stage = 'BorangWA2'
                WHERE id = ?";
        } else {
            // Insert new record
            $sql = "INSERT INTO wilayah_asal (
                jawatan_gred, email_penyelia, alamat_menetap_1, alamat_menetap_2,
                poskod_menetap, bandar_menetap, negeri_menetap, alamat_berkhidmat_1,
                alamat_berkhidmat_2, poskod_berkhidmat, bandar_berkhidmat, negeri_berkhidmat,
                tarikh_lapor_diri, pernah_guna, tarikh_terakhir_kemudahan, ada_pasangan,
                nama_first_pasangan, nama_last_pasangan, no_kp_pasangan, wilayah_menetap_pasangan,
                alamat_berkhidmat_1_pasangan, alamat_berkhidmat_2_pasangan, poskod_berkhidmat_pasangan,
                bandar_berkhidmat_pasangan, negeri_berkhidmat_pasangan, wilayah_asal_from_stage
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'BorangWA2')";
        }

        $stmt = $conn->prepare($sql);
        
        // Get form data
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
        $pernah_guna = $_POST['pernah_guna'];
        $tarikh_terakhir_kemudahan = $_POST['tarikh_terakhir_kemudahan'] ?? null;
        $ada_pasangan = $_POST['ada_pasangan'];
        $nama_first_pasangan = $_POST['nama_first_pasangan'] ?? null;
        $nama_last_pasangan = $_POST['nama_last_pasangan'] ?? null;
        $no_kp_pasangan = $_POST['no_kp_pasangan'] ?? null;
        $wilayah_menetap_pasangan = $_POST['wilayah_menetap_pasangan'] ?? null;
        $alamat_berkhidmat_1_pasangan = $_POST['alamat_berkhidmat_1_pasangan'] ?? null;
        $alamat_berkhidmat_2_pasangan = $_POST['alamat_berkhidmat_2_pasangan'] ?? null;
        $poskod_berkhidmat_pasangan = $_POST['poskod_berkhidmat_pasangan'] ?? null;
        $bandar_berkhidmat_pasangan = $_POST['bandar_berkhidmat_pasangan'] ?? null;
        $negeri_berkhidmat_pasangan = $_POST['negeri_berkhidmat_pasangan'] ?? null;

        if ($wilayah_asal_id) {
            // Update existing record
            $stmt->bind_param("sssssssssssssssssssssssssi",
                $jawatan_gred, $email_penyelia, $alamat_menetap_1, $alamat_menetap_2,
                $poskod_menetap, $bandar_menetap, $negeri_menetap, $alamat_berkhidmat_1,
                $alamat_berkhidmat_2, $poskod_berkhidmat, $bandar_berkhidmat, $negeri_berkhidmat,
                $tarikh_lapor_diri, $pernah_guna, $tarikh_terakhir_kemudahan, $ada_pasangan,
                $nama_first_pasangan, $nama_last_pasangan, $no_kp_pasangan, $wilayah_menetap_pasangan,
                $alamat_berkhidmat_1_pasangan, $alamat_berkhidmat_2_pasangan, $poskod_berkhidmat_pasangan,
                $bandar_berkhidmat_pasangan, $negeri_berkhidmat_pasangan, $wilayah_asal_id
            );
        } else {
            // Insert new record
            $stmt->bind_param("sssssssssssssssssssssssss",
                $jawatan_gred, $email_penyelia, $alamat_menetap_1, $alamat_menetap_2,
                $poskod_menetap, $bandar_menetap, $negeri_menetap, $alamat_berkhidmat_1,
                $alamat_berkhidmat_2, $poskod_berkhidmat, $bandar_berkhidmat, $negeri_berkhidmat,
                $tarikh_lapor_diri, $pernah_guna, $tarikh_terakhir_kemudahan, $ada_pasangan,
                $nama_first_pasangan, $nama_last_pasangan, $no_kp_pasangan, $wilayah_menetap_pasangan,
                $alamat_berkhidmat_1_pasangan, $alamat_berkhidmat_2_pasangan, $poskod_berkhidmat_pasangan,
                $bandar_berkhidmat_pasangan, $negeri_berkhidmat_pasangan
            );
        }

        if ($stmt->execute()) {
            if (!$wilayah_asal_id) {
                $wilayah_asal_id = $stmt->insert_id;
            }
            $_SESSION['wilayah_asal_id'] = $wilayah_asal_id;
            
            // Store form data in session
            $_SESSION['borangWA_data'] = [
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
                'pernah_guna' => $pernah_guna,
                'tarikh_terakhir_kemudahan' => $tarikh_terakhir_kemudahan,
                'ada_pasangan' => $ada_pasangan,
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

            // Redirect to the next form
            header("Location: ../borangWA2.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
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