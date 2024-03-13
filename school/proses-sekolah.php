<?php
require_once "../service/config.php";


if (isset($_POST['simpan'])) {
    $id             = $_POST['id'];
    $nama           = trim(htmlspecialchars($_POST['nama']));
    $email          = trim(htmlspecialchars($_POST['email']));
    $status         = $_POST['status'];
    $akreditasi     = $_POST['akreditasi'];
    $alamat         = trim(htmlspecialchars($_POST['alamat']));
    $visimisi       = trim(htmlspecialchars($_POST['visimisi']));
    $gbr            = trim(htmlspecialchars($_POST['gbrLama']));

    if ($_FILES['images']['error'] === 4) {
        $gbrSekolah = $gbr;
    } else {
        $url = 'profile-sekolah.php';
        $gbrSekolah = uploadimg($url);
        @unlink('../asset/image/' . $gbr);
    }
    mysqli_query($koneksi, "UPDATE sekolah SET
                            nama = '$nama',
                            alamat = '$alamat',
                            akreditasi = '$akreditasi',
                            status = '$status',
                            email = '$email',
                            visimisi = '$visimisi',
                            gambar  = '$gbrSekolah'
                            WHERE id = '$id'
                            ");
    header("location:profile-sekolah.php?'msg=update'");
    return;
}