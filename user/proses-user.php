<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../service/config.php";

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

//jika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    //ambil value elemen yg diposting
    $username = trim(htmlspecialchars($_POST['username']));
    $nama = trim(htmlspecialchars($_POST['nama']));
    $jabatan = $_POST['jabatan'];
    $alamat = trim(htmlspecialchars($_POST['alamat']));
    $gambar = trim(htmlspecialchars($_FILES['image']['name']));
    $password = 1234;
    $pass = password_hash($password, PASSWORD_DEFAULT);

    $alamat_encrypted = encryptDataWithPublicKey($alamat, '../path/to/public_key.pem');

    //cek username
    $cekUsername = mysqli_query($koneksi, "SELECT * from user WHERE username = '$username'");
    if (mysqli_num_rows($cekUsername) > 0) {
        header("location:add-user.php?msg=cancel");
        return;
    }

    //upload gambar
    if ($gambar != null) {
        $url = 'add-user.php';
        $gambar = uploadimg($url);
    } else {
        $gambar = 'default.png';
    }

    mysqli_query($koneksi, "INSERT INTO user VALUES(null,'$username','$pass','$nama','$alamat_encrypted','$jabatan','$gambar')");

    header("location:add-user.php?msg=added");
    return;
}
