<?php
//this is .pdf format
session_start();
include '../../../connection.php';


if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

// Set session timeout duration (in seconds)
$timeout_duration = 900; // 900 seconds = 15 minutes

// Check if the timeout is set and whether it has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
   // Session expired
   session_unset();
   session_destroy();
   header("Location: /reAllTras/login.php?timeout=1");
   exit();
}
// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();


$admin_name = $_SESSION['admin_name'];
$admin_id = $_SESSION['admin_id'];
$admin_role = $_SESSION['admin_role'];
$admin_icNo = $_SESSION['admin_icNo'];
$admin_email = $_SESSION['admin_email'];
$admin_phoneNo = $_SESSION['admin_phoneNo'];


if (isset($_GET['kp'])) {
  $kp = $_GET['kp'];

// Fetch user data from database
$sql = "SELECT * FROM user JOIN wilayah_asal ON user.kp = wilayah_asal.user_kp WHERE user.kp = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kp);
$stmt->execute();
$result = $stmt->get_result();
$application_data = $result->fetch_assoc();


setlocale(LC_TIME, 'ms_MY'); // Optional: for Malay locale (if supported)
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone

$bulan_malay = [
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
    'December' => 'Disember'
];

// Get today's date parts
$day = date("j");                
$english_month = date("F");     
$month_malay = $bulan_malay[$english_month]; 
$year = date("Y");             

$full_date = "$day $month_malay $year";


$fullName = $application_data['nama_first'] . ' ' . $application_data['nama_last'];
$fullNameCleaned = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
$fullNameCleaned = str_replace(' ', '_', $fullNameCleaned);

$filename = "Kelulusan Kemudahan Tambang Ziarah Wilayah_" . $fullNameCleaned . ".pdf";

?>

<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($filename) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
    @media print {
      @page {
        size: A4;
        margin: 3mm;
        text-align: justify;
      }

      .footer-line {
      -webkit-print-color-adjust: exact; /* For Safari/Chrome */
      print-color-adjust: exact;         /* For Firefox */
    }

    .print-footer-bar {
      height: 4px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .yellow-bar {
      background-color: yellow;
    }

    .blue-bar {
      background-color: blue;
    }

      html, body {
        margin: 0;
        font-family: "Arial", serif;
        font-size: 11pt;
        height: 297mm; /* height of A4 */
        position: relative;
        text-align: justify;
      }

      .page-break {
        page-break-before: always;
      }

      footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 9pt;
        text-align: center;
        border-top: 1px solid black;
        padding-top: 3px;
        background: white;
      }

      .no-print {
        display: none;
      }
    }

    body {
      font-family: "Arial", serif;
      margin: 20mm;
      font-size: 11pt;
      margin-top: 10px !important;
      padding-top: 10px !important;
      /* text-align: justify; */
    }

    header, .logo-area, .letterhead {
      margin-top: 0 !important;
      padding-top: 0 !important;
    }


    img {
    max-width: 100px !important; /* Resize large images */
    height: auto;
  }

    table {
    width: 80%;
      border: none;
    }

    td {
      padding: 0px;
      vertical-align: middle;
    }

    .table-penempatan {
        background-color: #f9f9f9;  /* Light grey background */
        border: 2px solid #333;     /* Darker border */
        font-size: 10pt;            /* Custom font size */
        margin-left: 30px;
        margin-right: 100px;
        width: 90%;
        position: center;
        }
        .table-penempatan td {
        padding: 7px;
        }


    .text-center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

  </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: flex-start;">
  <!-- Left Logo -->
  <div style="flex: 0 0 80px;">
    <img src="../../../assets/jataNegara.png" alt="jataNegara" width="100">
  </div>

  <!-- Middle Address -->
  <div style="flex: 5; padding: 0 10px; font-size: 12px;">
    <p><b>JABATAN KASTAM DIRAJA MALAYSIA</b><br>
    Ibu Pejabat Kastam Diraja Malaysia<br>
    Bahagian Khidmat Pengurusan dan Sumber Manusia<br>
    Cawangan Khidmat Pengurusan<br>
    Aras 9 Utara, Kompleks Kementerian Kewangan<br>
    No.3, Persiaran Perdana, Presint 2<br>
    <b>62596 PUTRAJAYA</b></p>
  </div>

  <!-- Right Contact Info -->
  <div style="text-align: center; flex:0 0 220px; position: relative; margin-top: -10px; margin-left: -40px;">
  <img src="../../../assets/JKDMLogo.png" alt="JKDM" width="80"><br>
<div style="text-align: justify; font-size: 12px;">
<table style="text-align: left; width: 100%;">
<col style="width: 40%;">
<col style="width: 60%;">
  <tr>
    <td><b>Telefon</b></td>
    <td>: +603 8882 2100</td>
  </tr>
  <tr>
    <td><b>Faksimile</b></td>
    <td>: +603 8889 5880</td>
  </tr>
  <tr>
    <td><b>Laman Web</b></td>
    <td>: <a href="http://www.customs.gov.my" style="color: black; text-decoration: none;">www.customs.gov.my</a></td>
  </tr>
</table>

  </div>
  </div>
</div>
<hr style="margin-bottom: 0px; margin-top: 0px;">
<table style="text-align: left; width: 60%;">
  <colgroup>
    <col style="width: 10%;">
    <col style="width: 50%;">
  </colgroup>
  <tr>
    <td><strong>Ruj.Tuan &nbsp;</strong></td>
    <td>:<span style="display:inline-block; width:150px;"></span></td>

    
  </tr>
  <tr>
    <td><strong>Ruj. Kami &nbsp;</strong></td>
    <td>:<span style="display:inline-block; width:150px;"></span></td>

    
  </tr>
  <tr>  
    <td><strong>Tarikh &nbsp;</strong></td>
    <td>:&nbsp; <?= $full_date ?></strong></td>
  </tr>
</table>

<br><p>
Ketua Bahagian<br>
Khidmat Pengurusan dan Sumber Manusia<br>
WP Kuala Lumpur<br>
<strong>(u.p: <span style="display:inline-block; width:200px;"></span></strong>
</p>

<div class="section">
  <p>Tuan/Puan,</p>
  <p style="margin-bottom: 0px; margin-top: 0px;"><strong>KELULUSAN KEMUDAHAN TAMBANG ZIARAH WILAYAH TAHUN 2025</strong></p>

  <table style="text-align: left;">
  <colgroup>
    <col style="width: 33%;">
    <col style="width: 67%;">
  </colgroup>
  <tr>
    <td><b>NAMA</b></td>
    <td> <strong>: <?= htmlspecialchars($application_data['nama_first'] . ' ' . $application_data['nama_last']) ?></strong></td>
  </tr>
  <tr>
    <td><b>JAWATAN</b></td>
    <td> <strong>: <?= htmlspecialchars($application_data['jawatan_gred']) ?></strong></td>
  </tr>
  <tr>  
    <td><b>WILAYAH ASAL</b></td>
    <td> <strong>: <?= htmlspecialchars($application_data['negeri_menetap']) ?></strong></td>
  </tr>
</table>

  <br><p>Dengan hormatnya saya diarahkan merujuk kepada perkara di atas.</p>

  <ol start="2">
    <li> &nbsp;&nbsp; Sukacita dimaklumkan bahawa permohonan pegawai di atas untuk menuntut Kemudahan Tambang Ziarah Wilayah adalah <strong>DILULUSKAN</strong> oleh Ketua Jabatan seperti berikut:</li>
  </ol>

  <table class="table table-bordered table-penempatan" style="border-collapse: collapse; border: 1px solid black; margin-top:-10px;">
  <colgroup>
    <col style="width: 33%;">
    <col style="width: 3%;">
    <col style="width: 54%;">
  </colgroup>
    <tr>
      <td><strong>Tarikh penempatan di wilayah penempatan</strong></td>
      <td>:</td>
      <?php 
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
      ?>
      <td><?=$tarikh_lapor_diri?></td>
    </tr>
    <tr>

    <?php
    $currentYear = date("Y");
    ?>
      <td><strong>Tempoh penggunaan</strong></td>
      <td>:</td>
      <td>01 Januari <?php echo $currentYear; ?> hingga 31 Disember <?php echo $currentYear; ?></td>
    </tr>
    <tr>
      <td><strong>Jenis kemudahan</strong></td>
      <td>:</td>
        <td>
        <?php
          if ($application_data['jenis_permohonan'] == 'diri_sendiri') {
              echo 'Diri sendiri/ pasangan/ anak ke ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain';
          } else {
              echo 'Keluarga pegawai dari ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain';
          }?>
        </td>
    </tr>
    <tr>
      <td><strong>Tarikh penggunaan</strong></td>
      <td>:</td>
      <?php 
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
      ?>
      <td><?=$tarikh_penggunaan?></td>
    </tr>
  </table>

  <?php
  $nextYear = date("Y", strtotime("+1 year"));
  ?>
  <ol start="3">
    <li>
    &nbsp;&nbsp; &nbsp;&nbsp; Untuk makluman, kemudahan Tambang Ziarah Wilayah diberi <strong>sekali dalam tempoh (1) tahun kalendar</strong>. Kemudahan yang tidak digunakan dalam tempoh satu (1) tahun kalendar akan luput dan tidak boleh dibawa ke tahun berikutnya. Tarikh pegawai boleh menggunakan kemudahan seterusnya ialah mulai <strong>01 Januari  <?php echo $nextYear; ?> </strong> hingga <strong>31 Disember  <?php echo $nextYear; ?> </strong>.
    </li>
    <br><li>
    &nbsp;&nbsp; Bersama-sama ini dikembalikan borang permohonan yang telah diluluskan untuk tindakan pihak Puan selanjutnya. Kelulusan ini hendaklah direkodkan ke dalam Buku Perkhidmatan pegawai.
    </li>
  </ol>

  <div class="page-break"></div>

<br><br>
<p><table style="text-align: left; width: 50%; margin-bottom: 70px;">
<colgroup>
    <col style="width: 10%;">
    <col style="width: 40%;">
  </colgroup>
  <tr>
    <td>Ruj.Kami</td>
    <td>:<span style="display:inline-block; width:150px;"></span></td>
  </tr>
</table>
</p>
<p>Sekian, terima kasih.</p><br>

<p><strong>"MALAYSIA MADANI"</strong><br></p>
<p><strong>"BERKHIDMAT UNTUK NEGARA"</strong><br></p>
<p>Saya yang menjalankan amanah,<br><br><br><br></p>





<br>
<br><br>
Bahagian Khidmat Pengurusan dan Sumber Manusia,<br>
Cawangan Khidmat Pengurusan,<br>
b.p Ketua Pengarah Kastam, <br>
Malaysia 

</p>

</div>


<footer style="text-align: center;">
  <div style="display: inline-block; text-align: center;">
    <b><p style="font-size: 9pt; margin: 0;">CEKAP ‧ TANGKAS ‧ INTEGRITI</p></b>
    <div class="print-footer-bar yellow-bar" style="height: 3px; background-color: yellow; margin-top: 2px;"></div>
    <div class="print-footer-bar blue-bar" style="height: 3px; background-color: blue;"></div>
  </div>
</footer>

<?php 
}

?>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
