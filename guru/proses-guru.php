<?php
session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
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
    $nip = htmlspecialchars($_POST['nip']);
    $nama = htmlspecialchars($_POST['nama']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $agama = $_POST['agama'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars($_FILES['image']['name']);

    $telepon_encrypted = encryptDataWithPublicKey($telepon, '../path/to/public_key.pem');
    $alamat_encrypted = encryptDataWithPublicKey($alamat, '../path/to/public_key.pem');

    $cekNip = mysqli_query($koneksi, "SELECT nip FROM guruku WHERE nip = '$nip'");
    if (mysqli_num_rows($cekNip) > 0) {
        header('location:add-guru.php?msg=cancel');
        exit;
    }

    if ($foto != null) {
        $url = "add-guru.php";
        $foto = uploadimg($url);
    } else {
        $foto = 'default.png';
    }

    mysqli_query($koneksi, "INSERT INTO guruku VALUES (null, '$nip', '$nama', '$alamat_encrypted', '$telepon_encrypted', '$agama', '$foto')");
    echo $alamat_encrypted;
    header("location:add-guru.php?msg=added");
    exit;
}

if (isset($_POST['update'])) {
    $id         = $_POST['id'];
    $nip        = htmlspecialchars($_POST['nip']);
    $nama       = htmlspecialchars($_POST['nama']);
    $telepon    = htmlspecialchars($_POST['telepon']);
    $agama      = $_POST['agama'];
    $alamat     = htmlspecialchars($_POST['alamat']);
    $foto       = htmlspecialchars($_POST['fotoLama']);

    $telepon_encrypted = encryptDataWithPublicKey($telepon, '../path/to/public_key.pem');
    $alamat_encrypted = encryptDataWithPublicKey($alamat, '../path/to/public_key.pem');


    $sqlGuru    = mysqli_query($koneksi, "SELECT * FROM guruku WHERE id='$id'");
    $data = mysqli_fetch_array($sqlGuru);
    $curNIP = $data['nip'];

    $newNIP = mysqli_query($koneksi, "SELECT nip FROM guruku WHERE nip = '$nip'");

    if ($nip !== $curNIP) {
        if (mysqli_num_rows($newNIP) > 0) {
            header("location:guru.php?msg=cancel");
            return;
        }
    }

    if ($_FILES['image']['error'] === 4) {
        $fotoGuru = $foto;
    } else {
        $url = "guru.php";
        $fotoGuru = uploadimg($url);
        if ($foto !== 'default.png') {
            @unlink('../asset/image/' . $foto);
        }
    }

    mysqli_query($koneksi, "UPDATE guruku SET
                           nip = '$nip',
                           nama = '$nama',
                           telepon = '$telepon_encrypted',
                           agama = '$agama',
                           alamat = '$alamat_encrypted',
                           foto = '$fotoGuru'
                           WHERE id = $id   
                    ");

    header("location:guru.php?msg=updated");
    return;
}
