<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM buku WHERE id = $id");

header("location:buku.php?msg=deleted");