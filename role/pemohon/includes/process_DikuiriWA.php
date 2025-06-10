<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO wilayah_asal (
            user_kp, jawatan_gred, email_penyelia,
            alamat_menetap_1, alamat_menetap_2, poskod_menetap, bandar_menetap, negeri_menetap,
            alamat_berkhidmat_1, alamat_berkhidmat_2, poskod_berkhidmat, bandar_berkhidmat, negeri_berkhidmat,
            tarikh_lapor_diri, tarikh_terakhir_kemudahan,
            nama_first_pasangan, nama_last_pasangan, no_kp_pasangan,
            alamat_berkhidmat_1_pasangan, alamat_berkhidmat_2_pasangan,
            poskod_berkhidmat_pasangan, bandar_berkhidmat_pasangan, negeri_berkhidmat_pasangan,
            wilayah_menetap_pasangan
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        
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

        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssssss",
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

        // Execute the statement
        if ($stmt->execute()) {
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
            $_SESSION['wilayah_asal_id'] = $stmt->insert_id;

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wilayah_asal_id = $_POST['wilayah_asal_id'] ?? null;
    
    if (!$wilayah_asal_id) {
        $_SESSION['error'] = "Invalid request";
        header("Location: ../wilayahAsal.php");
        exit();
    }

    // Prepare update fields based on database columns
    $updateFields = [
        'jawatan_gred' => $_POST['jawatan_gred'],
        'email_penyelia' => $_POST['email_penyelia'],
        'alamat_menetap_1' => $_POST['alamat_menetap_1'],
        'alamat_menetap_2' => $_POST['alamat_menetap_2'],
        'poskod_menetap' => $_POST['poskod_menetap'],
        'bandar_menetap' => $_POST['bandar_menetap'],
        'negeri_menetap' => $_POST['negeri_menetap'],
        'alamat_berkhidmat_1' => $_POST['alamat_berkhidmat_1'],
        'alamat_berkhidmat_2' => $_POST['alamat_berkhidmat_2'],
        'poskod_berkhidmat' => $_POST['poskod_berkhidmat'],
        'bandar_berkhidmat' => $_POST['bandar_berkhidmat'],
        'negeri_berkhidmat' => $_POST['negeri_berkhidmat'],
        'tarikh_lapor_diri' => $_POST['tarikh_lapor_diri'],
        'tarikh_terakhir_kemudahan' => $_POST['tarikh_terakhir_kemudahan'] ?? null,
        'nama_first_pasangan' => $_POST['nama_first_pasangan'] ?? null,
        'nama_last_pasangan' => $_POST['nama_last_pasangan'] ?? null,
        'no_kp_pasangan' => $_POST['no_kp_pasangan'] ?? null,
        'alamat_berkhidmat_1_pasangan' => $_POST['alamat_berkhidmat_1_pasangan'] ?? null,
        'alamat_berkhidmat_2_pasangan' => $_POST['alamat_berkhidmat_2_pasangan'] ?? null,
        'poskod_berkhidmat_pasangan' => $_POST['poskod_berkhidmat_pasangan'] ?? null,
        'bandar_berkhidmat_pasangan' => $_POST['bandar_berkhidmat_pasangan'] ?? null,
        'negeri_berkhidmat_pasangan' => $_POST['negeri_berkhidmat_pasangan'] ?? null,
        'wilayah_menetap_pasangan' => $_POST['wilayah_menetap_pasangan'] ?? null,
        // Add required status fields
        'wilayah_asal_form_fill' => true,
        'wilayah_asal_from_stage' => 'Hantar',
        'status_permohonan' => 'Belum Disemak',
        'kedudukan_permohonan' => 'Pemohon'
    ];

    // Build SQL query
    $sql = "UPDATE wilayah_asal SET ";
    $updates = [];
    $types = "";
    $values = [];
    
    foreach ($updateFields as $field => $value) {
        $updates[] = "`$field` = ?";
        $types .= "s";
        $values[] = $value;
    }
    
    $sql .= implode(', ', $updates);
    $sql .= " WHERE id = ?";
    $types .= "i";
    $values[] = $wilayah_asal_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Permohonan berjaya dikemaskini";
        header("Location: ../wilayahAsal.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating application: " . $conn->error;
        header("Location: ../dikuiriWA.php");
        exit();
    }
} else {
    header("Location: ../wilayahAsal.php");
    exit();
}
?>