<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $sampul = htmlspecialchars($_POST['sampul']);
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

if (isset($_POST['Ubah Status'])) {
    // string sql coba2 ubah id
    /*
    UPDATE buku SET 
    id_user_peminjam = 7,
    id_user_pelayanan = 1
    WHERE id = 5;
    */
}
