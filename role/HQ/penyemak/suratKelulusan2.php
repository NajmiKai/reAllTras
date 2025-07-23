<?php
//this is .docs format
session_start();
include '../../../connection.php';
require '../../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;


if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../../login.php");
    exit();
}

$timeout_duration = 900;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../../../login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (isset($_GET['kp'])) {
    $kp = $_GET['kp'];

    // Fetch user data
    $sql = "SELECT * FROM user JOIN wilayah_asal ON user.kp = wilayah_asal.user_kp WHERE user.kp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kp);
    $stmt->execute();
    $result = $stmt->get_result();
    $application_data = $result->fetch_assoc();

    // Generate date in Malay
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $bulan_malay = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Mac', 'April' => 'April',
        'May' => 'Mei', 'June' => 'Jun', 'July' => 'Julai', 'August' => 'Ogos',
        'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Disember'
    ];
    $day = date("j");
    $english_month = date("F");
    $month_malay = $bulan_malay[$english_month];
    $year = date("Y");
    $full_date = "$day $month_malay $year";

     // Convert to timestamp
     $date = strtotime($application_data['tarikh_lapor_diri']);

     // Format date as "dd Month yyyy" in Malay
     setlocale(LC_TIME, 'ms_MY.UTF-8'); // Set Malay locale


       // Convert to timestamp
       $date = strtotime($application_data['tarikh_lapor_diri']);

       // Format date as "dd Month yyyy" in Malay
       setlocale(LC_TIME, 'ms_MY.UTF-8'); // Set Malay locale

        // Format date using strftime
        $tarikh_lapor_diri = strftime('%d %B %Y', $date);

    // Check if month name is in English (fallback check)
    if (preg_match('/January|February|March|April|May|June|July|August|September|October|November|December/', $tarikh_lapor_diri)) {
        // Malay months map
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Mac',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Jun',
            'July' => 'Julai',
            'August' => 'Ogos',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Disember',
        ];
    
        $tarikh_lapor_diri = strtr($tarikh_lapor_diri, $months);
    }

      // Convert to timestamp
      $date = strtotime($application_data['tarikh_penerbangan_pergi']);

      // Format date as "dd Month yyyy" in Malay
      setlocale(LC_TIME, 'ms_MY.UTF-8'); // Set Malay locale

      // Format date using strftime
    $tarikh_penggunaan = strftime('%d %B %Y', $date);

    // Check if month name is in English (fallback check)
    if (preg_match('/January|February|March|April|May|June|July|August|September|October|November|December/', $tarikh_penggunaan)) {
        // Malay months map
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Mac',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Jun',
            'July' => 'Julai',
            'August' => 'Ogos',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Disember',
        ];
        // Replace English month with Malay month
        $tarikh_penggunaan = strtr($tarikh_penggunaan, $months);
    }

    $currentYear = date("Y");

    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    $phpWord->addTableStyle('NoBorder', [
        'borderSize' => 0,
        'borderColor' => 'FFFFFF',
        'cellMargin' => 0,
    ]);
    
    

    // Gaya umum
    $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 10,]);
    $section = $phpWord->addSection([
        // 'lang' => ['val' => 'MS-MY', 'bidi' => false],
        'paragraph' => ['spacing' => 100],
        'font' => ['name' => 'Arial', 'size' => 8],
        'lineSpacing' => 360,
        'lineSpacingRule' => 'multiple',
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, // 'both' means justify
        'marginTop' => 500, // reduce this 
        'marginBottom' => 300,
        'marginLeft' => 900,
        'marginRight' => 900,
        'footerHeight' => 300, // make footer height small
    ]);
    

    $table = $section->addTable(['borderSize' => 0,  'borderColor' => 'FFFFFF', 'cellMargin' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);

    // Row
    $table->addRow();
    
    // Left Logo (Jata Negara)
    $leftCell = $table->addCell(2000, ['valign' => 'top', 'borderSize' => 0,  'borderColor' => 'FFFFFF']);
    $leftCell->addImage('C:/xampp/htdocs/reAllTras/assets/jataNegara.png', ['width' => 70, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
    
    // Middle Address
    $paragraphStyle = ['spaceAfter' => 120, 'spaceBefore' => 120];

    $middleCell = $table->addCell(6000, ['valign' => 'top', 'borderSize' => 0,  'borderColor' => 'FFFFFF']);
    $middleCell->addText("JABATAN KASTAM DIRAJA MALAYSIA", ['bold' => true, 'size' => 8], $paragraphStyle);
    $middleCell->addText("Ibu Pejabat Kastam Diraja Malaysia", ['size' => 8], $paragraphStyle);
    $middleCell->addText("Bahagian Khidmat Pengurusan dan Sumber Manusia", ['size' =>8], $paragraphStyle);
    $middleCell->addText("Cawangan Khidmat Pengurusan", ['size' => 8], $paragraphStyle);
    $middleCell->addText("Aras 9 Utara, Kompleks Kementerian Kewangan", ['size' => 8], $paragraphStyle);
    $middleCell->addText("No.3, Persiaran Perdana, Presint 2", ['size' => 8], $paragraphStyle);
    $middleCell->addText("62596 PUTRAJAYA", ['bold' => true, 'size' => 8], $paragraphStyle);
    
    // Right Contact Info + Logo
    $rightCell = $table->addCell(4000, ['valign' => 'top', 'borderSize' => 0,  'borderColor' => 'FFFFFF']);
    $rightCell->addImage('C:/xampp/htdocs/reAllTras/assets/JKDMLogo.png', ['width' => 50, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    
    $rightCell->addText("Telefon: +603 8882 2100", ['size' => 8], $paragraphStyle);
    $rightCell->addText("Faksimile: +603 8889 5880", ['size' => 8], $paragraphStyle);
    $rightCell->addText("Laman Web: www.customs.gov.my", ['size' => 8], $paragraphStyle);
    
    $section->addText('', [], ['borderBottomSize' => 6, 'borderBottomColor' => '000000']);

    // === 1. Rujukan & Tarikh Section (as table with no border) ===
    $tableRef = $section->addTable('NoBorder');

    $tableRef->addRow();
    $tableRef->addCell(1000)->addText("Ruj. Tuan", ['bold' => true]);
    $tableRef->addCell(6000)->addText(":                           (   )");

    $tableRef->addRow();
    $tableRef->addCell(1000)->addText("Ruj. Kami", ['bold' => true]);
    $tableRef->addCell(6000)->addText(":                           (    )");

    $tableRef->addRow();
    $tableRef->addCell(1000)->addText("Tarikh", ['bold' => true]);
    $tableRef->addCell(6000)->addText(": " . $full_date);

    $section->addTextBreak(1);


    // Penerima
    $section->addText("Ketua Bahagian");
    $section->addText("Khidmat Pengurusan dan Sumber Manusia");
    $section->addText("Unit I, (WP Kuala Lumpur)");
    $section->addText("(u.p.:                                )", ['bold' => true]);

    $section->addText("Puan,", ['bold' => true], ['underline' => 'single']);
    // $section->addTextBreak(1);


    // Tajuk surat
    $section->addText("KELULUSAN KEMUDAHAN TAMBANG ZIARAH WILAYAH TAHUN 2025", ['bold' => true, 'underline' => 'single'], ['alignment' => 'left']);

    // Create table with 'NoBorder' style
    $tableInfo = $section->addTable('NoBorder');

    // First row - NAMA
    $tableInfo->addRow();
    $tableInfo->addCell(3000)->addText("NAMA", ['bold' => true]);
    $tableInfo->addCell(10000)->addText(": " . strtoupper($application_data['nama_first'] . ' ' . $application_data['nama_last']), ['bold' => true]);

    // Second row - JAWATAN
    $tableInfo->addRow();
    $tableInfo->addCell(3000)->addText("JAWATAN", ['bold' => true]);
    $tableInfo->addCell(10000)->addText(": " . strtoupper($application_data['jawatan_gred']), ['bold' => true]);

    // Third row - WILAYAH ASAL
    $tableInfo->addRow();
    $tableInfo->addCell(3000)->addText("WILAYAH ASAL", ['bold' => true]);
    $tableInfo->addCell(10000)->addText(": " . strtoupper($application_data['negeri_menetap']), ['bold' => true]);
    $section->addTextBreak(1);

    $section->addText("Dengan hormatnya saya diarah merujuk kepada perkara di atas.");

    // Perenggan 2
    $section->addText("2. Sukacita dimaklumkan bahawa permohonan pegawai di atas untuk menuntut Kemudahan Tambang Ziarah Wilayah adalah DILULUSKAN oleh Ketua Jabatan seperti berikut:", null,  $paragraphStyle);
    $section->addTextBreak(1);


    // Jadual
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
    $table->addRow();
    $table->addCell(5000)->addText("Tarikh penempatan di wilayah penempatan", ['bold' => true]);
    $table->addCell(500)->addText(':');
    $table->addCell(5000)->addText($tarikh_lapor_diri);

    $table->addRow();
    $table->addCell(5000)->addText("Tempoh penggunaan", ['bold' => true]);
    $table->addCell(500)->addText(':');
    $table->addCell(5000)->addText("01 Januari $currentYear hingga 31 Disember $currentYear");


    $table->addRow();
    $table->addCell(5000)->addText("Jenis kemudahan", ['bold' => true]);
    $table->addCell(500)->addText(':');
    $jenisText = ($application_data['jenis_permohonan'] == 'diri_sendiri') ? 
    'Diri sendiri/ pasangan/ anak ke ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain' : 
    'Keluarga pegawai dari ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain';
    $table->addCell(5000)->addText($jenisText);

    $table->addRow();
    $table->addCell(5000)->addText("Tarikh penggunaan");
    $table->addCell(500)->addText(':');
    $table->addCell(5000)->addText($tarikh_penggunaan);

    $section->addTextBreak(1);

    // Perenggan 3
    $section->addText("3. Untuk makluman, kemudahan Tambang Ziarah Wilayah diberi sekali dalam tempoh (1) tahun kalendar. Kemudahan yang tidak digunakan dalam tempoh satu (1) tahun kalendar akan luput dan tidak boleh dibawa ke tahun berikutnya. Tarikh pegawai boleh menggunakan kemudahan seterusnya ialah mulai 01 Januari 2026.", null, $paragraphStyle);
    $section->addTextBreak(1);

    // === Perenggan 4 ===
    $section->addText("4. Bersama-sama ini dikembalikan borang permohonan yang telah diluluskan untuk tindakan pihak Puan selanjutnya. Kelulusan ini hendaklah direkodkan ke dalam Buku Perkhidmatan pegawai.", null, $paragraphStyle);

    // Add small spacing to push to bottom if needed
    $section->addTextBreak(1);

    // === Compress footer and move rest to second page ===
    $section->addPageBreak();

    // === 3. Second "Ruj. Kami" Table (50%) ===
    $tableMiniRef = $section->addTable('NoBorder');
    $tableMiniRef->addRow();
    $tableMiniRef->addCell(2000)->addText("");
    $tableMiniRef->addCell(8000)->addText(":                                 (      )");

    $section->addTextBreak(1);
    $section->addText("Sekian, terima kasih.");
    $section->addTextBreak(1);
    $section->addText('"MALAYSIA MADANI"', ['bold' => true]);
    $section->addText('"BERKHIDMAT UNTUK NEGARA"', ['bold' => true]);
    $section->addText("Saya yang menjalankan amanah,");
    $section->addTextBreak(3); // Reduce spacing

    // Signature block
    $section->addText("(                                          )");
    $section->addText("Bahagian Khidmat Pengurusan dan Sumber Manusia,");
    $section->addText("Cawangan Khidmat Pengurusan,");
    $section->addText("b.p Ketua Pengarah Kastam,");
    $section->addText("Malaysia");

    // === Compact footer ===
    $footer = $section->addFooter();
    $table = $footer->addTable([
        'alignment' => Jc::CENTER,
        'width' => 3000,
        'borderSize' => 0,
        'cellMargin' => 0,
    
    ]);
    $table->addRow();
    $cell = $table->addCell(4000, ['valign' => 'center']);
    $cell->addText("CEKAP ‧ TANGKAS ‧ INTEGRITI", ['bold' => true, 'size' => 8], ['alignment' => Jc::CENTER]);

    $table->addRow([
        'height' => 100, // smaller height in twips (default ~400)
        'exactHeight' => true,
    ]);
    $cell = $table->addCell(4000, [
        'bgColor' => 'FFD700',
        'valign' => 'center',
        'borderSize' => 0,
        'height' => 60,
    ]);

    $table->addRow([
        'height' => 100, // smaller height in twips (default ~400)
        'exactHeight' => true,
    ]);
    $cell = $table->addCell(4000, [
        'bgColor' => '0000FF',
        'valign' => 'center',
        'borderSize' => 0,
        'height' => 60,
    ]);

    // Output
    $filename = "surat_kelulusan_tambang.docx";
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Cache-Control: must-revalidate');

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save("php://output");
    exit;
}
?>