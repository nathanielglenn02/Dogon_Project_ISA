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
    $tahunbuku = htmlspecialchars($_POST['tahun-buku']);

    $cekIsbn = mysqli_query($koneksi, "SELECT isbn FROM buku WHERE isbn = '$isbn'");
    if (mysqli_num_rows($cekIsbn) > 0) {
        header('location:add-buku.php?msg=cancel');
        exit;
    }

    if ($sampul != null) {
        $url = "add-buku.php";
        $sampul = uploadimg($url);
    } else {
        $sampul = "default-cover-book.png";
    }

    mysqli_query($koneksi, "INSERT INTO buku VALUES (null, '$sampul', '$isbn', '$judul', '$penerbit', '$tahunbuku', 1, 'EMP', null, null);");

    echo "<script>
                alert('Buku baru berhasil disimpan');
                document.location.href = 'add-buku.php';
        </script>";
    return;
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
