<?php
include_once '../../../includes/config.php';

// Fetch wilayah_asal and user data by KP
$kp = $_GET['kp'] ?? $_POST['kp'] ?? null;
$data = null;

$sql = "SELECT w.*, u.id AS user_id, u.nama_first, u.nama_last, u.kp AS user_kp, u.bahagian, u.email, u.phone
        FROM wilayah_asal w 
        LEFT JOIN user u ON w.user_kp = u.kp 
        WHERE w.user_kp = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kp);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$wilayah_id = $data['id'] ?? null;


$fullName = $data['nama_first'] . ' ' . $data['nama_last'];
$fullNameCleaned = preg_replace('/[^A-Za-z0-9\- ]/', '', $fullName);
$fullNameCleaned = str_replace(' ', '_', $fullNameCleaned);

$currentYear = date("Y");

$filename = "Borang Permohonan Tambang Ziarah Wilayah " .$currentYear . " " . $fullNameCleaned . ".pdf";

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
  <title><?= htmlspecialchars($filename) ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 100px;
            font-size: 14px;
        }
        h2, h3 {
            text-align: center;
            font-size:16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        td {
            border: 1px solid black;
            padding: 6px;
            vertical-align: top;
            font-size: 14px;
        }
        .section-title {
            background-color: #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 15px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .section-sub-title {
            font-weight: bold;
            text-align: center;
            font-size: 14px;
        }
        /* Print-specific overrides */
        @media print {
            body {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                font-size: 14px;

            }
            table {
                width: 99% !important;
                margin: 0 !important;
                font-size: 14px;
            }
            td {
                padding: 6px !important;
                font-size: 14px;
            }
            
            .section-title {
                background-color: #ddd !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <div style="text-align: right; font-weight: bold; margin-bottom: 0; margin-top:0">
        Lampiran C
    </div>
    <h3 style="text-align: center; margin-top: 0;">
        BORANG PERMOHONAN<br>KEMUDAHAN TAMBANG ZIARAH WILAYAH TAHUN 2025
    </h3>
    
    
    <table>
    <colgroup>
        <col style="width: 40%;">
        <col style="width: 60%;">
    </colgroup> 
        <tr><td colspan="4" class="section-title">MAKLUMAT PEGAWAI</td></tr>
        <tr>
            <td>Nama Pegawai:</td>
            <td colspan="3"><?php echo isset($data['nama_first'], $data['nama_last']) ? htmlspecialchars($data['nama_first'] . ' ' . $data['nama_last']) : '-'; ?></td>
        </tr>
        <tr>
            <td>No. Kad Pengenalan:</td>
            <td colspan="3"> <?php echo isset($data['user_kp']) ? htmlspecialchars($data['user_kp']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Jawatan & Gred:</td>
            <td colspan="3"><?php echo isset($data['jawatan_gred']) ? htmlspecialchars($data['jawatan_gred']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Alamat Tempat Tinggal:</td>
            <td colspan="3">
    <?php 
    if (
        !empty($data['alamat_menetap_1']) ||
        !empty($data['alamat_menetap_2']) ||
        !empty($data['poskod_menetap']) ||
        !empty($data['bandar_menetap']) ||
        !empty($data['negeri_menetap'])
    ) {
        $alamat = htmlspecialchars($data['alamat_menetap_1'] ?? '') . ", " .
                  htmlspecialchars($data['alamat_menetap_2'] ?? '') . ", " .
                  htmlspecialchars($data['poskod_menetap'] ?? '') . ' ' .
                  htmlspecialchars($data['bandar_menetap'] ?? '') . ', ' .
                  htmlspecialchars($data['negeri_menetap'] ?? '');

        echo nl2br(trim($alamat));
    } else {
        echo '-';
    }
    ?>
</td>

        </tr>
        <tr>
            <td>Alamat Tempat Berkhidmat:</td>
            <td colspan="3">
            <?php 
            if (
                !empty($data['alamat_berkhidmat_1']) ||
                !empty($data['alamat_berkhidmat_2']) ||
                !empty($data['poskod_berkhidmat']) ||
                !empty($data['bandar_berkhidmat']) ||
                !empty($data['negeri_berkhidmat'])
            ) {
                $alamat = htmlspecialchars($data['alamat_berkhidmat_1'] ?? '') . ", " .
                        htmlspecialchars($data['alamat_berkhidmat_2'] ?? '') . ", " .
                        htmlspecialchars($data['poskod_berkhidmat'] ?? '') . ' ' .
                        htmlspecialchars($data['bandar_berkhidmat'] ?? '') . ', ' .
                        htmlspecialchars($data['negeri_berkhidmat'] ?? '');

                echo nl2br(trim($alamat));
            } else {
                echo '-';
            }
            ?>
        </td>
        </tr>
        <tr>
            <td>Tarikh Lapor Diri Di Wilayah Berkhidmat:</td>
            <td colspan="3"><?php echo isset($data['tarikh_lapor_diri']) && $data['tarikh_lapor_diri'] !== '' ? htmlspecialchars($data['tarikh_lapor_diri']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Tarikh Terakhir Kemudahan Digunakan:</td>
            <td colspan="3"><?php echo isset($data['tarikh_terakhir_kemudahan']) && $data['tarikh_terakhir_kemudahan'] !== '' ? htmlspecialchars($data['tarikh_terakhir_kemudahan']) : '-'; ?></td>
        </tr>

        <tr><td colspan="4" class="section-title">MAKLUMAT PASANGAN</td></tr>
        <tr>
            <td>Nama:</td>
            <td colspan="3"><?php echo isset($data['nama_first_pasangan'], $data['nama_last_pasangan']) ? htmlspecialchars($data['nama_last_pasangan'] . ' ' . $data['nama_last_pasangan']) : '-'; ?></td>
        </tr>
        <tr>
            <td>No. Kad Pengenalan:</td>
            <td colspan="3"><?php echo isset($data['no_kp_pasangan']) && $data['no_kp_pasangan'] !== '' ? htmlspecialchars($data['no_kp_pasangan']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Alamat Tempat Berkhidmat (jika berkenaan):</td>
            <td colspan="3">
            <?php 
            if (
                !empty($data['alamat_berkhidmat_1_pasangan']) ||
                !empty($data['alamat_berkhidmat_2_pasangan']) ||
                !empty($data['poskod_berkhidmat_pasangan']) ||
                !empty($data['bandar_berkhidmat_pasangan']) ||
                !empty($data['negeri_berkhidmat_pasangan'])
            ) {
                $alamat = htmlspecialchars($data['alamat_berkhidmat_1_pasangan'] ?? '') . ", " .
                        htmlspecialchars($data['alamat_berkhidmat_2_pasangan'] ?? '') . ", " .
                        htmlspecialchars($data['poskod_berkhidmat_pasangan'] ?? '') . ' ' .
                        htmlspecialchars($data['bandar_berkhidmat_pasangan'] ?? '') . ', ' .
                        htmlspecialchars($data['negeri_berkhidmat_pasangan'] ?? '');

                echo nl2br(trim($alamat));
            } else {
                echo '-';
            }
            ?>
        </td>
        </tr>
        <tr>
            <td>Wilayah Menetap:</td>
            <td colspan="3"><?php echo isset($data['wilayah_menetap_pasangan']) && $data['wilayah_menetap_pasangan'] !== '' ? htmlspecialchars($data['wilayah_menetap_pasangan']) : '-'; ?></td>
        </tr>

        <tr><td colspan="4" class="section-title">MAKLUMAT WILAYAH MENETAP IBU BAPA</td></tr>
        <tr><td colspan="4" class="section-sub-title">Maklumat Bapa</td></tr>
        <tr>
            <td>Nama Bapa:</td>
            <td colspan="3"><?php echo isset($data['nama_bapa']) && $data['nama_bapa'] !== '' ? htmlspecialchars($data['nama_bapa']) : '-'; ?></td>
        </tr>
        <tr>
            <td>No. Kad Pengenalan Bapa:</td>
            <td colspan="3"><?php echo isset($data['no_kp_bapa']) && $data['no_kp_bapa'] !== '' ? htmlspecialchars($data['no_kp_bapa']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Wilayah Menetap Yang Diisytiharkan:</td>
            <td colspan="3"><?php echo isset($data['wilayah_menetap_bapa']) && $data['wilayah_menetap_bapa'] !== '' ? htmlspecialchars($data['wilayah_menetap_bapa']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Alamat Semasa Wilayah Menetap Bapa*:</td>
            <td colspan="3">
            <?php 
            if (
                !empty($data['alamat_menetap_1_bapa']) ||
                !empty($data['alamat_menetap_2_bapa']) ||
                !empty($data['poskod_menetap_bapa']) ||
                !empty($data['bandar_menetap_bapa']) ||
                !empty($data['negeri_menetap_bapa'])
            ) {
                $alamat = htmlspecialchars($data['alamat_menetap_1_bapa'] ?? '') . ", " .
                        htmlspecialchars($data['alamat_menetap_2_bapa'] ?? '') . ", " .
                        htmlspecialchars($data['poskod_menetap_bapa'] ?? '') . ' ' .
                        htmlspecialchars($data['bandar_menetap_bapa'] ?? '') . ', ' .
                        htmlspecialchars($data['negeri_menetap_bapa'] ?? '');

                echo nl2br(trim($alamat));
            } else {
                echo '-';
            }
            ?>
        </td>
        </tr>
        <tr>
            <td>Ibu Negeri/Bandar Utama Hendak Dituju**:</td>
            <td colspan="3"><?php echo isset($data['ibu_negeri_bandar_dituju_bapa']) && $data['ibu_negeri_bandar_dituju_bapa'] !== '' ? htmlspecialchars($data['ibu_negeri_bandar_dituju_bapa']) : '-'; ?></td>
        </tr>

        <tr><td colspan="4" class="section-sub-title">Maklumat Ibu</td></tr>
        <tr>
            <td>Nama Ibu:</td>
            <td colspan="3"><?php echo isset($data['nama_ibu']) && $data['nama_ibu'] !== '' ? htmlspecialchars($data['nama_ibu']) : '-'; ?></td>
        </tr>
        <tr>
            <td>No. Kad Pengenalan Ibu:</td>
            <td colspan="3"><?php echo isset($data['no_kp_ibu']) && $data['no_kp_ibu'] !== '' ? htmlspecialchars($data['no_kp_ibu']) : '-'; ?></td>
    </tr>
    <tr>
            <td>Wilayah Menetap Yang Diisytiharkan:</td>
            <td colspan="3"><?php echo isset($data['wilayah_menetap_ibu']) && $data['wilayah_menetap_ibu'] !== '' ? htmlspecialchars($data['wilayah_menetap_ibu']) : '-'; ?></td>
        </tr>
        <tr>
            <td>Alamat Semasa Wilayah Menetap Ibu*:</td>
            <td colspan="3">
            <?php 
            if (
                !empty($data['alamat_menetap_1_ibu']) ||
                !empty($data['alamat_menetap_2_ibu']) ||
                !empty($data['poskod_menetap_ibu']) ||
                !empty($data['bandar_menetap_ibu']) ||
                !empty($data['negeri_menetap_ibu'])
            ) {
                $alamat = htmlspecialchars($data['alamat_menetap_1_ibu'] ?? '') . ", " .
                        htmlspecialchars($data['alamat_menetap_2_ibu'] ?? '') . ", " .
                        htmlspecialchars($data['poskod_menetap_ibu'] ?? '') . ' ' .
                        htmlspecialchars($data['bandar_menetap_ibu'] ?? '') . ', ' .
                        htmlspecialchars($data['negeri_menetap_ibu'] ?? '');

                echo nl2br(trim($alamat));
            } else {
                echo '-';
            }
            ?>
        </td>
        </tr>
        <tr>
            <td>Ibu Negeri/Bandar Utama Hendak Dituju**:</td>
            <td colspan="3"><?php echo isset($data['ibu_negeri_bandar_dituju_ibu']) && $data['ibu_negeri_bandar_dituju_ibu'] !== '' ? htmlspecialchars($data['ibu_negeri_bandar_dituju_ibu']) : '-'; ?></td>
        </tr>
    </table>


    <div style="page-break-before: always;"></div><br><br>

    <table border="0" width="100%" cellpadding="5" cellspacing="0">
    <tr>
        <td colspan="4" class="section-title">PERMOHONAN PEGAWAI</td>
    </tr>
    <?php $isDiriSendiri = $data['jenis_permohonan'] == 'diri_sendiri';?>
<!-- START: Diri Sendiri Section -->
<tr>
    <td colspan="4" style="border-bottom: none;">
        <br>( <?php echo $isDiriSendiri ? '&check;' : '&nbsp;&nbsp;'; ?> ) Diri sendiri/ pasangan/ anak*** ke ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain*** 
    </td>
</tr>
<tr>
    <td colspan="4" style="border-top: none; border-bottom:none">
        <table border="0" width="100%" cellpadding="5" cellspacing="0">
            <tr>
                <td colspan="2" class="section-title">Maklumat Perjalanan Pegawai</td>
                <td colspan="2" class="section-title">Maklumat Perjalanan Pasangan</td>
            </tr>
            <tr>
                <td>Tarikh Penerbangan (Pergi):</td>
                <td>Tarikh Penerbangan (Balik):</td>
                <td>Tarikh Penerbangan (Pergi):</td>
                <td>Tarikh Penerbangan (Balik):</td>
            </tr>
            <tr>
                <td><?php echo $isDiriSendiri && !empty($data['tarikh_penerbangan_pergi']) ? htmlspecialchars($data['tarikh_penerbangan_pergi']) : '-'; ?></td>
                <td><?php echo $isDiriSendiri && !empty($data['tarikh_penerbangan_balik']) ? htmlspecialchars($data['tarikh_penerbangan_balik']) : '-'; ?></td>
                <td><?php echo $isDiriSendiri && !empty($data['tarikh_penerbangan_pergi_pasangan']) ? htmlspecialchars($data['tarikh_penerbangan_pergi_pasangan']) : '-'; ?></td>
                <td><?php echo $isDiriSendiri && !empty($data['tarikh_penerbangan_balik_pasangan']) ? htmlspecialchars($data['tarikh_penerbangan_balik_pasangan']) : '-'; ?></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding-top:20px; border-top: none; border-bottom: none;">
        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <tr><td colspan="6" class="section-title">Maklumat Perjalanan Anak</td></tr>
            <tr class="section-title">
                <td>Bil.</td>
                <td>Nama</td>
                <td>Tarikh Lahir</td>
                <td>No. Kad Pengenalan/ MyKid</td>
                <td>Tarikh Penerbangan (Pergi)</td>
                <td>Tarikh Penerbangan (Balik)</td>
            </tr>
            <?php
            $sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $wilayah_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $bil = 1;
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $bil++; ?>.</td>
                    <td><?php echo $isDiriSendiri && (!empty($row['nama_first_pengikut']) || !empty($row['nama_last_pengikut'])) ? htmlspecialchars(($row['nama_first_pengikut'] ?? '') . ' ' . ($row['nama_last_pengikut'] ?? ''))  : '-'; ?></td>
                    <td><?php echo $isDiriSendiri ? htmlspecialchars($row['tarikh_lahir_pengikut']) : '-'; ?></td>
                    <td><?php echo $isDiriSendiri ? htmlspecialchars($row['kp_pengikut']) : '-'; ?></td>
                    <td><?php echo $isDiriSendiri && !empty($row['tarikh_penerbangan_pergi_pengikut']) 
                        ? htmlspecialchars($row['tarikh_penerbangan_pergi_pengikut']) 
                        : '-'; ?></td>
                    <td><?php echo $isDiriSendiri && !empty($row['tarikh_penerbangan_balik_pengikut']) 
                        ? htmlspecialchars($row['tarikh_penerbangan_balik_pengikut']) 
                        : '-'; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </td>
</tr>
<tr><td colspan="6" style="border-top: none; border-bottom: none; padding-bottom:50px">
    <em>*sila lampirkan tambahan nama anak sekiranya perlu</em>
</td></tr>
<!-- END: Diri Sendiri Section -->


  
    <br>
    <?php $isKeluarga = $data['jenis_permohonan'] == 'keluarga';?>
<!-- START: Keluarga Section -->
<tr>
    <td style="border-bottom: none;">
        <p>( <?php echo $isKeluarga ? '&check;' : '&nbsp;&nbsp;'; ?> ) Keluarga pegawai dari ibu negeri/ bandar utama*** di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain*** ke wilayah berkhidmat</p>

    </td>
</tr>
<tr>
    <td style="border-top: none; border-bottom: none;">
        <table width="100%" cellpadding="5" cellspacing="0">
            <tr class="section-title">
                <td>Bil.</td>
                <td>Nama Pasangan</td>
                <td>Tarikh Perjalanan (Pergi)</td>
                <td>Tarikh Perjalanan (Balik)</td>
            </tr>
            <tr>
                <td>1.</td>
                <td><?php echo $isKeluarga && (!empty($data['nama_first_pasangan']) || !empty($data['nama_last_pasangan'])) ? htmlspecialchars(($data['nama_first_pasangan'] ?? '') . ' ' . ($data['nama_last_pasangan'] ?? ''))  : '-'; ?></td>
                <td><?php echo $isKeluarga && !empty($data['tarikh_penerbangan_pergi_pasangan']) ? htmlspecialchars($data['tarikh_penerbangan_pergi_pasangan']) : '-'; ?></td>
                <td><?php echo $isKeluarga && !empty($data['tarikh_penerbangan_balik_pasangan']) ? htmlspecialchars($data['tarikh_penerbangan_balik_pasangan']) : '-'; ?></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding-top:20px; border-top: none; border-bottom: none;">
        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <tr><td colspan="6" class="section-title">Maklumat Perjalanan Anak</td></tr>
            <tr class="section-title">
                <td>Bil.</td>
                <td>Nama</td>
                <td>Tarikh Lahir</td>
                <td>No. Kad Pengenalan/ MyKid</td>
                <td>Tarikh Penerbangan (Pergi)</td>
                <td>Tarikh Penerbangan (Balik)</td>
            </tr>
            <?php
            $sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $wilayah_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $bil = 1;
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $bil++; ?>.</td>
                    <td><?php echo $isKeluarga && (!empty($row['nama_first_pengikut']) || !empty($row['nama_last_pengikut'])) ? htmlspecialchars(($row['nama_first_pengikut'] ?? '') . ' ' . ($row['nama_last_pengikut'] ?? ''))  : '-'; ?></td>
                    <td><?php echo $isKeluarga ? htmlspecialchars($row['tarikh_lahir_pengikut']) : '-'; ?></td>
                    <td><?php echo $isKeluarga ? htmlspecialchars($row['kp_pengikut']) : '-'; ?></td>
                    <td><?php echo $isKeluarga && !empty($row['tarikh_penerbangan_pergi_pengikut']) 
                        ? htmlspecialchars($row['tarikh_penerbangan_pergi_pengikut']) 
                        : '-'; ?></td>
                    <td><?php echo $isKeluarga && !empty($row['tarikh_penerbangan_balik_pengikut']) 
                        ? htmlspecialchars($row['tarikh_penerbangan_balik_pengikut']) 
                        : '-'; ?></td>
                                </tr>
            <?php endwhile; ?>
        </table>
    </td>
</tr>
<tr><td style="border-top: none; padding-bottom:50px">
    <em>*sila lampirkan tambahan nama anak sekiranya perlu</em>
</td></tr>
<!-- END: Keluarga Section -->
    </table>
        </td></tr>


    <div style="page-break-before: always;"></div><br><br>
    <table border="0" width="100%" cellpadding="5" cellspacing="0">
    <tr>
        <td colspan="4" class="section-title">PENGESAHAN PEGAWAI</td>
    </tr>
    <tr>
        <td colspan="6" style="padding-bottom:0px;">
            Saya mengesahkan bahawa semua maklumat dan kenyataan yang diberikan adalah benar dan sah.
            Saya juga memahami bahawa sekiranya terdapat maklumat palsu, tidak benar atau tidak lengkap,
            maka saya boleh dikenakan tindakan tatatertib di bawah Peraturan-Peraturan Pegawai Awam
            (Kelakuan dan Tatatertib) 1993. <br><br><br><br>

            Tandatangan : <br><br>


            Nama : <br><br>



            Tarikh : <br><br>
        </td>
    </tr>

    <tr>
        <td colspan="4" class="section-title">PENGESAHAN JABATAN</td>
    </tr>
    <tr>
        <td style="width: 50%;">Markah Penilaian Prestasi Terakhir:</td>
        <td><?php echo isset($data['markah_prestasi_user']) && $data['markah_prestasi_user'] !== '' ? htmlspecialchars($data['markah_prestasi_user']) : '-'; ?></td>
    </tr>
    <tr>
        <td>Hukuman Tatatertib Pada Tahun Permohonan:</td>
        <td><?php echo isset($data['hukuman_tatatertib_user']) && $data['hukuman_tatatertib_user'] !== '' ? htmlspecialchars($data['hukuman_tatatertib_user']) : '-'; ?></td>
    </tr>

    <tr>
        <td colspan="4" class="section-title">KEPUTUSAN KETUA JABATAN</td>
    </tr>
    <tr>
        <td colspan="6"> <br>
        <?php 
        $keputusan = null;
        
        if ($data['status_permohonan'] == 'Selesai') {
            $keputusan = 'DILULUSKAN';
        } elseif ($data['status_permohonan'] == 'Dikuiri') {
            $keputusan = 'TIDAK DILULUSKAN';
        } else
             $keputusan = 'SEDANG DIPROSES';
        ?>

        Permohonan pegawai seperti mana berikut adalah <b><?php echo $keputusan; ?></b> :-<br><br>

            <table>
            <tr>
            <td colspan="6">
                <label>
                    <input type="checkbox" name="jenis_kelulusan[]" value="diri_sendiri"  style="width: 20px; height: 20px; vertical-align: middle;"
                        <?php echo ($data['jenis_permohonan'] == 'diri_sendiri') ? 'checked' : ''; ?>>
                    Tambang bagi pegawai/pasangan/anak*** ke ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain***
                </label>
                <br><br>
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <label>
                    <input type="checkbox" name="jenis_kelulusan[]" value="keluarga"  style="width: 20px; height: 20px; vertical-align: middle;"
                        <?php echo ($data['jenis_permohonan'] == 'keluarga') ? 'checked' : ''; ?>>
                    Tambang kepada pasangan dan/ atau anak-anak*** dari ibu negeri/ bandar utama di wilayah menetap ibu bapa yang diisytiharkan/ wilayah lain*** ke wilayah berkhidmat pegawai
                </label>
                <br><br>
            </td>
        </tr>
        </table>
        <br><br>
            Tandatangan: <br><br>
            Nama: <br><br>
            Tarikh:<br>
        </td>
    </tr>
</table>

    

<p><strong>Catatan:</strong><br><br>
*  &nbsp;&nbsp;&nbsp;&nbsp; Sila sertakan dokumen sokongan/ pembuktian. Tidak terpakai bagi ibu bapa yang telah meninggal dunia.<br><br>
**  &nbsp;&nbsp;&nbsp;  Sekiranya ibu bapa telah meninggal dunia, sila sertakan Salinan Sijil Kematian.<br><br>
***  &nbsp;&nbsp;   Potong mana yang tidak berkaitan
</p>


</body>
</html>

