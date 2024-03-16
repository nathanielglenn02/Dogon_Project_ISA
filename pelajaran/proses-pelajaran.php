<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $pelajaran = htmlspecialchars($_POST['pelajaran']);
    $jurusan   = $_POST['jurusan'];
    $guru      = $_POST['guru'];

    $cekPelajaran = mysqli_query($koneksi, "SELECT * FROM pelajaran WHERE pelajaran = '$pelajaran'");
    if (mysqli_num_rows($cekPelajaran) > 0) {
        header("location:pelajaran.php?msg=cancel");
        return;
    }

    //$guru bermasalah alias kosong 
    mysqli_query($koneksi, "INSERT INTO pelajaran VALUES (null, '$pelajaran', '$jurusan', '$guru')");

    header("location: pelajaran.php?msg=added");
    return;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $pelajaran = htmlspecialchars($_POST['pelajaran']);
    $jurusan   = $_POST['jurusan'];
    $guru      = $_POST['guru'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $guru = $_POST['guru'];
    }

    $queryPelajaran = mysqli_query($koneksi, "SELECT * FROM pelajaran WHERE id = $id");
    $data = mysqli_fetch_array($queryPelajaran);
    $curPelajaran = $data['pelajaran'];

    $cekPelajaran = mysqli_query($koneksi, "SELECT * FROM pelajaran WHERE pelajaran = '$pelajaran'");

    if ($pelajaran !== $curPelajaran) {
        if (mysqli_num_rows($cekPelajaran) > 0) {
            header("location:pelajaran.php?msg=cancelupdate");
            return;
        }
    }

    mysqli_query($koneksi, "UPDATE pelajaran SET pelajaran = '$pelajaran', jurusan = '$jurusan', guru = '$guru' WHERE id = $id");

    header("location:pelajaran.php?msg=updated");
    return;
}
