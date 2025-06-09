<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get wilayah_asal form data
    $jenis_permohonan = $_POST['jenis_permohonan'];
    $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
    $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
    $start_point = $_POST['start_point'];
    $end_point = $_POST['end_point'];
    $tarikh_penerbangan_pergi_pasangan = isset($_POST['tarikh_penerbangan_pergi_pasangan']) ? $_POST['tarikh_penerbangan_pergi_pasangan'] : null;
    $tarikh_penerbangan_balik_pasangan = isset($_POST['tarikh_penerbangan_balik_pasangan']) ? $_POST['tarikh_penerbangan_balik_pasangan'] : null;

    // Update wilayah_asal table
    $update_wilayah_sql = "UPDATE wilayah_asal SET 
        jenis_permohonan = ?,
        tarikh_penerbangan_pergi = ?,
        tarikh_penerbangan_balik = ?,
        start_point = ?,
        end_point = ?,
        tarikh_penerbangan_pergi_pasangan = ?,
        tarikh_penerbangan_balik_pasangan = ?
        WHERE id = ?";

    $update_wilayah_stmt = $conn->prepare($update_wilayah_sql);
    $update_wilayah_stmt->bind_param("sssssssi", 
        $jenis_permohonan,
        $tarikh_penerbangan_pergi,
        $tarikh_penerbangan_balik,
        $start_point,
        $end_point,
        $tarikh_penerbangan_pergi_pasangan,
        $tarikh_penerbangan_balik_pasangan,
        $wilayah_asal_data['id']
    );
    $update_wilayah_stmt->execute();

    // Get pengikut form data
    $nama_first_pengikut = $_POST['nama_first_pengikut'];
    $nama_last_pengikut = $_POST['nama_last_pengikut'];
    $kp_pengikut = $_POST['kp_pengikut'];
    $tarikh_lahir_pengikut = $_POST['tarikh_lahir_pengikut'];
    $tarikh_penerbangan_pergi_pengikut = $_POST['tarikh_penerbangan_pergi_pengikut'];
    $tarikh_penerbangan_balik_pengikut = $_POST['tarikh_penerbangan_balik_pengikut'];

    // Get existing pengikut records
    $existing_sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
    $existing_stmt = $conn->prepare($existing_sql);
    $existing_stmt->bind_param("i", $wilayah_asal_data['id']);
    $existing_stmt->execute();
    $existing_result = $existing_stmt->get_result();
    $existing_pengikut = [];
    while ($row = $existing_result->fetch_assoc()) {
        $existing_pengikut[$row['kp_pengikut']] = $row;
    }

    // Process each submitted pengikut
    foreach ($kp_pengikut as $index => $kp) {
        $data = [
            'nama_first_pengikut' => $nama_first_pengikut[$index],
            'nama_last_pengikut' => $nama_last_pengikut[$index],
            'kp_pengikut' => $kp,
            'tarikh_lahir_pengikut' => $tarikh_lahir_pengikut[$index],
            'tarikh_penerbangan_pergi_pengikut' => $tarikh_penerbangan_pergi_pengikut[$index],
            'tarikh_penerbangan_balik_pengikut' => $tarikh_penerbangan_balik_pengikut[$index]
        ];

        if (isset($existing_pengikut[$kp])) {
            // Check if any data has changed
            $existing = $existing_pengikut[$kp];
            $has_changes = false;
            foreach ($data as $key => $value) {
                if ($existing[$key] !== $value) {
                    $has_changes = true;
                    break;
                }
            }

            if ($has_changes) {
                // Update only if there are changes
                $update_sql = "UPDATE wilayah_asal_pengikut SET 
                    nama_first_pengikut = ?,
                    nama_last_pengikut = ?,
                    tarikh_lahir_pengikut = ?,
                    tarikh_penerbangan_pergi_pengikut = ?,
                    tarikh_penerbangan_balik_pengikut = ?
                    WHERE wilayah_asal_id = ? AND kp_pengikut = ?";
                
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("sssssss", 
                    $data['nama_first_pengikut'],
                    $data['nama_last_pengikut'],
                    $data['tarikh_lahir_pengikut'],
                    $data['tarikh_penerbangan_pergi_pengikut'],
                    $data['tarikh_penerbangan_balik_pengikut'],
                    $wilayah_asal_data['id'],
                    $kp
                );
                $update_stmt->execute();
            }
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO wilayah_asal_pengikut (
                wilayah_asal_id,
                nama_first_pengikut,
                nama_last_pengikut,
                kp_pengikut,
                tarikh_lahir_pengikut,
                tarikh_penerbangan_pergi_pengikut,
                tarikh_penerbangan_balik_pengikut
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("issssss", 
                $wilayah_asal_data['id'],
                $data['nama_first_pengikut'],
                $data['nama_last_pengikut'],
                $data['kp_pengikut'],
                $data['tarikh_lahir_pengikut'],
                $data['tarikh_penerbangan_pergi_pengikut'],
                $data['tarikh_penerbangan_balik_pengikut']
            );
            $insert_stmt->execute();
        }
    }

    $_SESSION['success'] = "Maklumat berjaya dikemaskini.";
    header("Location: wilayahAsal.php");
    exit();
}
?> 