<?php 

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $sampul = htmlspecialchars($_FILES['image']['name']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $judul = htmlspecialchars($_POST['judul']);
    $penerbit = htmlspecialchars($_POST['penerbit']);

    $cekIsbn = mysqli_query($koneksi, "SELECT isbn FROM buku WHERE isbn = '$isbn'");
    if (mysqli_num_rows($cekIsbn) > 0) {
        header('location:add-buku.php?msg=cancel');
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO buku VALUES (null, '$sampul', '$isbn', '$judul', '$penerbit', 1, 'EMP')");
    header("location:add-buku.php?msg=added");
    exit;
}

?>