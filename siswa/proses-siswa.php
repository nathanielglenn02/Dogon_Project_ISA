<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

function encryptDataWithPublicKey($data, $publicKeyPath)
{
    // Memuat kunci publik dari file
    $publicKeyContents = file_get_contents($publicKeyPath);
    if (!$publicKeyContents) {
        die("Gagal membaca kunci publik dari path: $publicKeyPath");
    }

    $publicKey = openssl_pkey_get_public($publicKeyContents);
    if (!$publicKey) {
        die("Gagal memuat kunci publik.");
    }

    // Melakukan enkripsi
    if (!openssl_public_encrypt($data, $encrypted, $publicKey)) {
        die("Gagal mengenkripsi data dengan kunci publik.");
    }
    return base64_encode($encrypted);
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars($_FILES['image']['name']);

    $alamat_encrypted = encryptDataWithPublicKey($alamat, '../path/to/public_key.pem');

    if ($foto != null) {
        $url = "add-siswa.php";
        $foto = uploadimg($url);
    } else {
        $foto = "default.png";
    }

    mysqli_query($koneksi, "INSERT INTO siswa VALUES('$nis','$nama','$alamat_encrypted','$kelas','$jurusan','$foto');");

    echo "<script>
                alert('Data siswa berhasil disimpan');
                document.location.href = 'add-siswa.php';
        </script>";
    return;
} else if (isset($_POST['update'])) {
    $nis = $_POST['nis'];
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars($_POST['fotoLama']);

    $alamat_encrypted = encryptDataWithPublicKey($alamat, '../path/to/public_key.pem');

    if ($_FILES['image']['error'] === 4) {
        $fotoSiswa = $foto;
    } else {
        $url = "siswa.php";
        $fotoSiswa = uploadimg($url);
        if ($foto != 'default.png') {
            @unlink('../asset/image/' . $foto);
        }
    }

    mysqli_query($koneksi, "UPDATE siswa SET 
                                nama        = '$nama', 
                                kelas       = '$kelas', 
                                jurusan     = '$jurusan', 
                                alamat      = '$alamat_encrypted', 
                                foto        = '$fotoSiswa'
                                WHERE nis   = '$nis'
                                ");
    echo "<script>
        alert('Data siswa berhasil di update..');
        document.location.href='siswa.php';
        </script>";
    return;
}
