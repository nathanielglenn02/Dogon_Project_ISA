<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit();
}

require_once "../service/config.php";
$title = "Siswa - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$privateKeyPath = '../path/to/private_key.pem';

function decryptDataWithPrivateKey($encryptedData, $privateKeyPath)
{
    if (!file_exists($privateKeyPath)) {
        die("File kunci privat tidak ditemukan: $privateKeyPath");
    }
    $privateKey = file_get_contents($privateKeyPath);
    $privateKeyResource = openssl_pkey_get_private($privateKey);
    if (!$privateKeyResource) {
        die("Gagal memuat kunci privat.");
    }
    openssl_private_decrypt(base64_decode($encryptedData), $decrypted, $privateKeyResource);
    return $decrypted;
}

$username = $_SESSION["ssUser"];
$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
$profile = mysqli_fetch_array($queryUser);

if ($profile['jabatan'] == "Guru") {
    $displayGuru = "visually-hidden";
} else {
    $displayGuru = "";
}

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = "";
}

$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data Siswa berhasil dihapus..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data Siswa berhasil diperbarui..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Data Siswa gagal diperbarui, nip sudah ada..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Siswa</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php" style="text-decoration: none; color: black;">Home</a>
                </li>
                <li class="breadcrumb-item active">Siswa</li>
            </ol>
            <?php
            if ($msg != "") {
                echo $alert;
            }
            ?>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i> Data Siswa</span>
                    <a href="<?= $main_url ?>siswa/add-siswa.php" class="btn btn-sm btn-primary float-end <?= $displayGuru ?>"><i class="fa-solid fa-plus"></i> Tambah Siswa</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">
                                    <center>Foto</center>
                                </th>
                                <th scope="col">
                                    <center>NIS</center>
                                </th>
                                <th scope="col">
                                    <center>Nama</center>
                                </th>
                                <th scope="col">
                                    <center>Kelas</center>
                                </th>
                                <th scope="col">
                                    <center>Jurusan</center>
                                </th>
                                <th scope="col">
                                    <center>Alamat</center>
                                </th>
                                <th scope="col">
                                    <center class="<?= $displayGuru ?>">Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $querySiswa = mysqli_query($koneksi, "SELECT * FROM siswa");

                            while ($data = mysqli_fetch_array($querySiswa)) {
                                $decryptedAlamat = decryptDataWithPrivateKey($data['alamat'], $privateKeyPath);
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++ ?></th>
                                    <td align="center">
                                        <center>
                                            <img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" alt="Foto Siswa" width="60px">
                                        </center>
                                    </td>
                                    <td><?= $data['nis'] ?></td>
                                    <td><?= $data['nama'] ?></td>
                                    <td><?= $data['kelas'] ?></td>
                                    <td><?= $data['jurusan'] ?></td>
                                    <td><?= $decryptedAlamat ?></td>
                                    <td align="center">
                                        <center>
                                            <a href="edit-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-sm btn-warning <?= $displayGuru ?>" title="Update Siswa"><i class="fa-solid fa-pen"></i></a>
                                            <a href="hapus-siswa.php?nis=<?= $data['nis'] ?>&foto=<?= $data['foto'] ?>" class="btn btn-sm btn-danger <?= $displayGuru ?>" title="Hapus Siswa" onclick="return confirm('Anda yakin akan menghapus data ini ?')"><i class="fa-solid fa-trash"></i>
                                        </center></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>




    <?php

    require_once "../template/footer.php";

    ?>