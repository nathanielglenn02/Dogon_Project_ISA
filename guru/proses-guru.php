<?php
session_start();

if(!isset($_SESSION['ssLogin'])){
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
if (isset($_POST['simpan'])) {
    $nip        = htmlspecialchars($_POST['nip']);
    $nama       = htmlspecialchars($_POST['nama']);
    $telepon   = htmlspecialchars($_POST['telepon']);
    $agama      = $_POST['agama'];
    $alamat     = htmlspecialchars($_POST['alamat']);
    $foto       = htmlspecialchars($_POST['image']['name']);


    $cekNip = mysqli_query($koneksi, "SELECT nip FROM guruku WHERE nip = '$nip'");

    if(mysqli_num_rows($cekNip) > 0 ){
        header('location:add-guru.php?msg=cancel');
        return;
    }

    if ($foto != null) {
        $url = "add-guru.php";
        $foto = uploadimg($url);
    } else{
        $foto = 'default.png';
    }

    mysqli_query($koneksi, "INSERT INTO guruku VALUES (null, '$nip', '$nama', '$alamat', '$telepon', '$agama', '$foto')");

    header("location:add-guru.php?msg=added");
    return;
}
?>