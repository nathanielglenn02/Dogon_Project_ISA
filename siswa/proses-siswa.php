<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars($_FILES['image']['name']);


    if ($foto != null) {
        $url = "add-siswa.php";
        $foto = uploadimg($url);
    } else {
        $foto = "default.png";
    }

    mysqli_query($koneksi, "INSERT INTO siswa VALUES('$nis','$nama','$alamat','$kelas','$jurusan','$foto');");

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
                                alamat      = '$alamat', 
                                foto        = '$fotoSiswa'
                                WHERE nis   = '$nis'
                                ");
    echo "<script>
        alert('Data siswa berhasil di update..');
        document.location.href='siswa.php';
        </script>";
    return;
}
?>