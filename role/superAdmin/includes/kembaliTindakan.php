<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start();
include_once '../../../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);
$wilayah_id = $data['wilayah_id'] ?? null;
$log_id = $data['log_id'] ?? null;


if (!$log_id) {
    echo json_encode(["message" => "ID tidak sah."]);
    exit;
}


$query = $conn->prepare("SELECT peranan, tindakan, catatan FROM document_logs WHERE id = ?");
if (!$query) {
    echo json_encode(['message' => 'Query failed: ' . $conn->error]);
    exit;
}

$query->bind_param("i", $log_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["message" => "Data tidak dijumpai."]);
    exit;
}


$peranan = $row['peranan'];
$tindakan = $row['tindakan'];
$editCatatan = $row['catatan'];

// Then delete AFTER using the values
$deleteQuery = $conn->prepare("DELETE FROM document_logs WHERE id = ?");
$deleteQuery->bind_param("i", $log_id);
$deleteQuery->execute();

// your long PHP logic starts here
$status = '';
$idColumn = null;
$tarikhColumn = null;
$ulasanColumn = null;

switch ($peranan) {
    case 'PBR CSM':
        if ($tindakan === 'Telah direkodkan di dalam buku log') {
            $status = 'Menunggu pengesahan penyemak HQ';
            $idColumn = 'pbr_csm2_id';
            $tarikhColumn = 'tarikh_keputusan_csm2';
        } else {
            $status = 'Menunggu pengesahan PBR CSM';
            $idColumn = 'pbr_csm1_id';
            $tarikhColumn = 'tarikh_keputusan_csm1';
            $ulasanColumn = 'ulasan_pbr_csm1';
        }
        break;
    
        case 'Pegawai Sulit CSM':
            $status = 'Menunggu pengesahan pegawai sulit CSM';
            $idColumn = 'pegSulit_csm_id';
            $tarikhColumn = 'tarikh_keputusan_pegSulit_csm';
            break;

        case 'Pengesah CSM':
            if ($tindakan === 'Sah/Perakuan I') {
                $status = 'Menunggu pengesahan pengesah CSM';
                $idColumn = 'pengesah_csm1_id';
                $tarikhColumn = 'tarikh_keputusan_pengesah_csm1';
                $ulasanColumn = 'ulasan_pengesah_csm1';
            } else {
                $status = 'Menunggu pengesahan pengesah2 CSM';
                $idColumn = 'pengesah_csm2_id';
                $tarikhColumn = 'tarikh_keputusan_pengesah_csm2';
                $ulasanColumn = 'ulasan_pengesah_csm1'; // Note: May be typo?
            }
            break;

        case 'Penyemak HQ':
            if ($tindakan === 'Telah muat naik surat kelulusan') {
                $status = 'Menunggu pengesahan penyemak2 HQ';
                $idColumn = 'penyemak_HQ2_id';
                $tarikhColumn = 'tarikh_keputusan_penyemak_HQ2';
            } else {
                $status = 'Menunggu pengesahan penyemak1 HQ';
                $idColumn = 'penyemak_HQ1_id';
                $tarikhColumn = 'tarikh_keputusan_penyemak_HQ1';
                $ulasanColumn = 'ulasan_penyemak_HQ';
            }
            break;

        case 'Pengesah HQ':
            $status = 'Menunggu pengesahan pengesah HQ';
            $idColumn = 'pengesah_HQ_id';
            $tarikhColumn = 'tarikh_keputusan_pengesah_HQ';
            $ulasanColumn = 'ulasan_pengesah_HQ';
            break;

        case 'Pelulus HQ':
            $status = 'Menunggu pengesahan pelulus HQ';
            $idColumn = 'pelulus_HQ_id';
            $tarikhColumn = 'tarikh_keputusan_pelulus_HQ';
            $ulasanColumn = 'ulasan_pelulus_HQ';
            break;

        case 'Penyemak Baki Kewangan':
            $status = 'Menunggu pengesahan penyemak baki kewangan';
            $idColumn = 'penyemakBaki_kewangan_id';
            $tarikhColumn = 'tarikh_keputusan_penyemakBaki_kewangan';
            $ulasanColumn = 'ulasan_penyemakBaki_kewangan';
            break;

        case 'Pengesah Kewangan':
            $status = 'Menunggu pengesahan pengesah kewangan';
            $idColumn = 'pengesah_kewangan_id';
            $tarikhColumn = 'tarikh_keputusan_pengesah_kewangan';
            $ulasanColumn = 'ulasan_pengesah_kewangan';
            break;
    
        default:
            $status = 'Menunggu pengesahan penyedia kemudahan kewangan';
            $idColumn = 'penyediaKemudahan_kewangan_id';
            $tarikhColumn = 'tarikh_keputusan_penyediaKemudahan_kewangan';
}

// Build dynamic SQL
$setCols = "status = ?";
$params = [$status];
$types = "s";

if ($idColumn) {
    $setCols .= ", `$idColumn` = NULL";
}
if ($tarikhColumn) {
    $setCols .= ", `$tarikhColumn` = NULL";
}
if ($ulasanColumn) {
    $setCols .= ", `$ulasanColumn` = NULL";
}

$sql = "UPDATE wilayah_asal SET $setCols WHERE id = ?";
$params[] = $wilayah_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["message" => "Rekod telah berjaya dikembalikan semula."]);
} else {
    echo json_encode(["message" => "Ralat ketika mengembalikan rekod."]);
}
?>
