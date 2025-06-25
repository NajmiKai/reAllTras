<?php
// cetak_wilayah_asal.php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';

use setasign\Fpdi\Tcpdf\Fpdi;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../loginUser.php");
    exit();
}

$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;
if (!$wilayah_asal_id) {
    die("Permohonan tidak dijumpai.");
}

// Fetch all the data like in wilayahAsal.php
// User data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];

// Wilayah Asal data
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// Pengikut data
$pengikut_sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$pengikut_stmt = $conn->prepare($pengikut_sql);
$pengikut_stmt->bind_param("i", $wilayah_asal_data['id']);
$pengikut_stmt->execute();
$pengikut_result = $pengikut_stmt->get_result();
$pengikut_data = [];
while ($row = $pengikut_result->fetch_assoc()) {
    $pengikut_data[] = $row;
}

// Documents data
$doc_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
$doc_stmt = $conn->prepare($doc_sql);
$doc_stmt->bind_param("i", $wilayah_asal_data['id']);
$doc_stmt->execute();
$doc_result = $doc_stmt->get_result();
$documents = [];
while ($row = $doc_result->fetch_assoc()) {
    $documents[] = $row;
}

// PDF Generation Start
$pdf = new Fpdi();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ALLTRAS');
$pdf->SetTitle('Laporan Permohonan Wilayah Asal - ' . $user_name);
$pdf->SetSubject('Laporan Permohonan Wilayah Asal');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// === HTML Content for PDF ===
$html = '<style>
    h1 {
        font-size: 18px;
        text-align: center;
        font-weight: bold;
    }
    h2 {
        font-size: 14px;
        font-weight: bold;
        margin-top: 15px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 10px;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .label {
        font-weight: bold;
        width: 30%;
    }
</style>';

$html .= '<h1>Laporan Permohonan Wilayah Asal</h1>';

// Maklumat Pegawai
$alamat_menetap = htmlspecialchars($wilayah_asal_data['alamat_menetap_1']) .
    ($wilayah_asal_data['alamat_menetap_2'] ? '<br>' . htmlspecialchars($wilayah_asal_data['alamat_menetap_2']) : '') . '<br>' .
    htmlspecialchars($wilayah_asal_data['poskod_menetap']) . ' ' . htmlspecialchars($wilayah_asal_data['bandar_menetap']) . '<br>' .
    htmlspecialchars($wilayah_asal_data['negeri_menetap']);

$alamat_berkhidmat = htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_1']) .
    ($wilayah_asal_data['alamat_berkhidmat_2'] ? '<br>' . htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_2']) : '') . '<br>' .
    htmlspecialchars($wilayah_asal_data['poskod_berkhidmat']) . ' ' . htmlspecialchars($wilayah_asal_data['bandar_berkhidmat']) . '<br>' .
    htmlspecialchars($wilayah_asal_data['negeri_berkhidmat']);

$html .= '<h2>Maklumat Pegawai</h2>';
$html .= '<table>';
$html .= '<tr><td class="label">Nama</td><td>' . htmlspecialchars($user_name) . '</td></tr>';
$html .= '<tr><td class="label">No. KP</td><td>' . htmlspecialchars($user_data['kp']) . '</td></tr>';
$html .= '<tr><td class="label">Jawatan & Gred</td><td>' . htmlspecialchars($wilayah_asal_data['jawatan_gred']) . '</td></tr>';
$html .= '<tr><td class="label">Email Penyelia</td><td>' . htmlspecialchars($wilayah_asal_data['email_penyelia']) . '</td></tr>';
$html .= '<tr><td class="label">Alamat Menetap</td><td>' . $alamat_menetap . '</td></tr>';
$html .= '<tr><td class="label">Alamat Berkhidmat</td><td>' . $alamat_berkhidmat . '</td></tr>';
if ($wilayah_asal_data['nama_first_pasangan'] || $wilayah_asal_data['nama_last_pasangan']) {
    $html .= '<tr><td class="label">Maklumat Pasangan</td><td>' . htmlspecialchars($wilayah_asal_data['nama_first_pasangan'] . ' ' . $wilayah_asal_data['nama_last_pasangan']) . '<br>No. KP: ' . htmlspecialchars($wilayah_asal_data['no_kp_pasangan']) . '</td></tr>';
}
$html .= '</table>';

// Maklumat Ibu Bapa
$html .= '<h2>Maklumat Ibu Bapa</h2>';
$html .= '<table>';
if ($wilayah_asal_data['nama_bapa']) {
    $alamat_bapa = htmlspecialchars($wilayah_asal_data['alamat_menetap_1_bapa']) .
    ($wilayah_asal_data['alamat_menetap_2_bapa'] ? '<br>' . htmlspecialchars($wilayah_asal_data['alamat_menetap_2_bapa']) : '') . '<br>' .
    htmlspecialchars($wilayah_asal_data['poskod_menetap_bapa']) . ' ' . htmlspecialchars($wilayah_asal_data['bandar_menetap_bapa']) . '<br>' .
    htmlspecialchars($wilayah_asal_data['negeri_menetap_bapa']);
    $html .= '<tr><td class="label">Maklumat Bapa</td><td>' . htmlspecialchars($wilayah_asal_data['nama_bapa']) . '<br>No. KP: ' . htmlspecialchars($wilayah_asal_data['no_kp_bapa']) . '<br>Wilayah Menetap: ' . htmlspecialchars($wilayah_asal_data['wilayah_menetap_bapa']) . '</td></tr>';
    $html .= '<tr><td class="label">Alamat Bapa</td><td>' . $alamat_bapa . '</td></tr>';
}
if ($wilayah_asal_data['nama_ibu']) {
    $alamat_ibu = htmlspecialchars($wilayah_asal_data['alamat_menetap_1_ibu']) .
    ($wilayah_asal_data['alamat_menetap_2_ibu'] ? '<br>' . htmlspecialchars($wilayah_asal_data['alamat_menetap_2_ibu']) : '') . '<br>' .
    htmlspecialchars($wilayah_asal_data['poskod_menetap_ibu']) . ' ' . htmlspecialchars($wilayah_asal_data['bandar_menetap_ibu']) . '<br>' .
    htmlspecialchars($wilayah_asal_data['negeri_menetap_ibu']);
    $html .= '<tr><td class="label">Maklumat Ibu</td><td>' . htmlspecialchars($wilayah_asal_data['nama_ibu']) . '<br>No. KP: ' . htmlspecialchars($wilayah_asal_data['no_kp_ibu']) . '<br>Wilayah Menetap: ' . htmlspecialchars($wilayah_asal_data['wilayah_menetap_ibu']) . '</td></tr>';
    $html .= '<tr><td class="label">Alamat Ibu</td><td>' . $alamat_ibu . '</td></tr>';
}
$html .= '</table>';


// Maklumat Penerbangan
$html .= '<h2>Maklumat Penerbangan</h2>';
$jenis_permohonan = $wilayah_asal_data['jenis_permohonan'] === 'diri_sendiri' ? 'Permohonan Diri Sendiri/Pengikut ke Wilayah Menetap' : 'Permohonan Keluarga Pegawai ke Wilayah Berkhidmat';
$html .= '<table>';
$html .= '<tr><td class="label">Jenis Permohonan</td><td>' . $jenis_permohonan . '</td></tr>';
$html .= '</table>';

$html .= '<h3>Pemohon</h3>';
$html .= '<table>';
$html .= '<tr><td class="label">Tarikh Penerbangan</td><td>Pergi: ' . date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi'])) . '<br>Balik: ' . date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik'])) . '</td></tr>';
$html .= '<tr><td class="label">Lokasi</td><td>Berlepas: ' . htmlspecialchars($wilayah_asal_data['start_point']) . '<br>Tiba: ' . htmlspecialchars($wilayah_asal_data['end_point']) . '</td></tr>';
$html .= '</table>';

if ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) {
    $html .= '<h3>Pasangan</h3>';
    $html .= '<table>';
    $html .= '<tr><td class="label">Tarikh Penerbangan</td><td>Pergi: ' . date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan'])) . '<br>Balik: ' . date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik_pasangan'])) . '</td></tr>';
    $html .= '</table>';
}

if ($pengikut_data) {
    foreach ($pengikut_data as $index => $pengikut) {
        $html .= '<h3>Pengikut ' . ($index + 1) . '</h3>';
        $html .= '<table>';
        $html .= '<tr><td class="label">Maklumat Pengikut</td><td>' . htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) . '<br>No. KP: ' . htmlspecialchars($pengikut['kp_pengikut']) . '<br>Tarikh Lahir: ' . date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) . '</td></tr>';
        $html .= '<tr><td class="label">Tarikh Penerbangan</td><td>Pergi: ' . date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) . '<br>Balik: ' . date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) . '</td></tr>';
        $html .= '</table>';
    }
}


$pdf->writeHTML($html, true, false, true, false, '');


// === Document Merging ===
foreach ($documents as $doc) {
    $filePath = realpath(__DIR__ . '/../../' . str_replace('../../../', '', $doc['file_path']));

    if ($filePath && file_exists($filePath)) {
        try {
            $file_extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $pdf->AddPage();
                $pdf->Image($filePath, 15, 15, 180, 0, '', '', '', true, 300, '', false, false, 0);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Ln(5);
                $pdf->Cell(0, 10, 'Dokumen: ' . htmlspecialchars($doc['file_name']), 0, 1, 'C');
            } elseif ($file_extension === 'pdf') {
                $pageCount = $pdf->setSourceFile($filePath);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($templateId);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId);
                }
            }
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(255, 0, 0); // Red color for error
            $pdf->MultiCell(0, 10, 'Tidak dapat memuatkan dokumen: ' . htmlspecialchars($doc['file_name']), 0, 'L', 0, 1, '', '', true);
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 10, 'Dokumen ini mungkin menggunakan format mampatan yang tidak disokong. Sila lihat dokumen ini secara berasingan.', 0, 'L', 0, 1, '', '', true);
        } catch (Exception $e) {
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(255, 0, 0); // Red color for error
            $pdf->MultiCell(0, 10, 'Ralat umum semasa memproses dokumen: ' . htmlspecialchars($doc['file_name']), 0, 'L', 0, 1, '', '', true);
        }
    }
}


// Output PDF
$pdf->Output('laporan_wilayah_asal.pdf', 'I');

?> 