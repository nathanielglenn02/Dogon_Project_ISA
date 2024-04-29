<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
require_once "../stegano/vendor/tecnickcom/tcpdf/tcpdf.php";

function encryptData($data, $key)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($data, $key)
{
    $data = base64_decode($data);
    $ivSize = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $ivSize);
    $encryptedData = substr($data, $ivSize);
    $decrypted = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return $decrypted;
}

$key = "classifiedDataUjian";

// Membuat instance TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Mengatur informasi dokumen
$pdf->SetCreator('Your Name');
$pdf->SetTitle('Laporan Hasil Ujian');

// Menambahkan halaman
$pdf->AddPage();

// Menetapkan font
$pdf->SetFont('helvetica', '', 12);

// Menambahkan teks ke PDF
$content = '<h2 style="text-align:center;">Laporan Hasil Ujian SMA DOGON</h2>';
$content .= '<table border="1" cellspacing="0" cellpadding="5">';
$content .= '<tr>
                <th width="70">NO Ujian</th>
                <th width="70">NIS</th>
                <th>Jurusan</th>
                <th width="80">Nilai Terendah</th>
                <th width="80">Nilai Tertinggi</th>
                <th width="80">Rata-rata</th>
                <th width="80">Hasil Ujian</th>
            </tr>';

// Mengambil data ujian dari database
$dataUjian = mysqli_query($koneksi, "SELECT * FROM ujian");
while ($row = mysqli_fetch_array($dataUjian)) {
    // Mengenkripsi data dari kolom nilai_terendah, nilai_tertinggi, nilai_rata, dan hasil_ujian
    $encryptedNilaiTerendah = encryptData($row['nilai_terendah'], $key);
    $encryptedNilaiTertinggi = encryptData($row['nilai_tertinggi'], $key);
    $encryptedNilaiRata = encryptData($row['nilai_rata'], $key);
    $encryptedHasilUjian = encryptData($row['hasil_ujian'], $key);

    $content .= '<tr>
                    <td align="center">' . $row['no_ujian'] . '</td>
                    <td align="center">' . $row['nis'] . '</td>
                    <td>' . $row['jurusan'] . '</td>
                    <td align="center">' . $encryptedNilaiTerendah . '</td>
                    <td align="center">' . $encryptedNilaiTertinggi . '</td>
                    <td align="center">' . $encryptedNilaiRata . '</td>
                    <td align="center">' . $encryptedHasilUjian . '</td>
                </tr>';
}

$content .= '</table>';

$pdf->writeHTML($content, true, false, true, false, '');

// Menyimpan PDF
$pdf->Output('output_stego.pdf', 'I');
