<?php
function getWilayahAsalDetails($conn, $wilayah_asal_id, $user_kp) {
    // First, get the wilayah_asal record
    $sql = "SELECT * FROM wilayah_asal WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $wilayah_asal = $result->fetch_assoc();

    // If no record found or user_kp doesn't match, return false
    if (!$wilayah_asal || $wilayah_asal['user_kp'] !== $user_kp) {
        return false;
    }

    return $wilayah_asal;
}

function getPengikutDetails($conn, $wilayah_asal_id) {
    $sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getDocuments($conn, $wilayah_asal_id) {
    $sql = "SELECT * FROM documents WHERE wilayah_asal_id = ? ORDER BY upload_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateWilayahAsal($conn, $wilayah_asal_id, $post_data) {
    // Fields that can be updated
    $updatable_fields = [
        'jawatan_gred', 'email_penyelia',
        'alamat_menetap_1', 'alamat_menetap_2', 'poskod_menetap', 'bandar_menetap', 'negeri_menetap',
        'alamat_berkhidmat_1', 'alamat_berkhidmat_2', 'poskod_berkhidmat', 'bandar_berkhidmat', 'negeri_berkhidmat',
        'tarikh_lapor_diri', 'tarikh_terakhir_kemudahan',
        'nama_first_pasangan', 'nama_last_pasangan', 'no_kp_pasangan',
        'alamat_berkhidmat_1_pasangan', 'alamat_berkhidmat_2_pasangan', 'poskod_berkhidmat_pasangan',
        'bandar_berkhidmat_pasangan', 'negeri_berkhidmat_pasangan', 'wilayah_menetap_pasangan',
        'nama_bapa', 'no_kp_bapa', 'wilayah_menetap_bapa',
        'alamat_menetap_1_bapa', 'alamat_menetap_2_bapa', 'poskod_menetap_bapa',
        'bandar_menetap_bapa', 'negeri_menetap_bapa', 'ibu_negeri_bandar_dituju_bapa',
        'nama_ibu', 'no_kp_ibu', 'wilayah_menetap_ibu',
        'alamat_menetap_1_ibu', 'alamat_menetap_2_ibu', 'poskod_menetap_ibu',
        'bandar_menetap_ibu', 'negeri_menetap_ibu', 'ibu_negeri_bandar_dituju_ibu',
        'tarikh_penerbangan_pergi', 'tarikh_penerbangan_balik',
        'tarikh_penerbangan_pergi_pasangan', 'tarikh_penerbangan_balik_pasangan',
        'start_point', 'end_point'
    ];

    $updates = [];
    $types = "";
    $values = [];

    foreach ($updatable_fields as $field) {
        if (isset($post_data[$field])) {
            $updates[] = "$field = ?";
            $types .= "s";
            $values[] = $post_data[$field];
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE wilayah_asal SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= "i";
        $values[] = $wilayah_asal_id;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    return false;
}

function updatePengikut($conn, $post_data) {
    $sql = "UPDATE wilayah_asal_pengikut SET 
            nama_first_pengikut = ?,
            nama_last_pengikut = ?,
            tarikh_lahir_pengikut = ?,
            tarikh_penerbangan_pergi_pengikut = ?,
            tarikh_penerbangan_balik_pengikut = ?
            WHERE id = ? AND wilayah_asal_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssii",
        $post_data['nama_first_pengikut'],
        $post_data['nama_last_pengikut'],
        $post_data['tarikh_lahir_pengikut'],
        $post_data['tarikh_penerbangan_pergi_pengikut'],
        $post_data['tarikh_penerbangan_balik_pengikut'],
        $post_data['pengikut_id'],
        $post_data['wilayah_asal_id']
    );
    return $stmt->execute();
}

function uploadDocument($conn, $wilayah_asal_id, $user_kp, $files, $post_data) {
    $target_dir = "../../uploads/documents/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($files["document"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $files["document"]["size"];

    if (move_uploaded_file($files["document"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin, file_origin_id) 
                VALUES (?, ?, ?, ?, ?, ?, 'pemohon', ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssiss",
            $wilayah_asal_id,
            $file_name,
            $target_file,
            $file_type,
            $file_size,
            $post_data['description'],
            $user_kp
        );
        return $stmt->execute();
    }
    return false;
}
?> 