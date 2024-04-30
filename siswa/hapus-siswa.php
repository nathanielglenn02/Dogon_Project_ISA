<?php
session_start();


if (!isset($_SESSION['ssLogin'])) {
    header("Location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

$id     = $_GET['nis'];
$foto   = $_GET['foto'];

mysqli_query($koneksi, "DELETE FROM siswa WHERE nis = '$id'");
if ($foto != 'default.png') {
    unlink('../asset/image/' . $foto);
}

header("location:siswa.php?msg=deleted");
return;
